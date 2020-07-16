<?php

namespace App\Service;

use App\Entity\Chapter;
use App\Entity\Project;
use App\Entity\Scene;
use App\Entity\User;
use App\Repository\ChapterRepository;
use App\Repository\ProjectRepository;
use App\Repository\SceneRepository;
use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ImportYwriteService
{
    private $projectRepository;
    private $chapterRepository;
    private $sceneRepository;
    private $zip;

    public function __construct(
        ProjectRepository $projectRepository,
        ChapterRepository $chapterRepository,
        SceneRepository $sceneRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->chapterRepository = $chapterRepository;
        $this->sceneRepository = $sceneRepository;
    }

    public function readZip(File $file, User $user)
    {
        $this->zip = new \ZipArchive();
        $this->zip->open($file->getPathname());
        $yWrite = null;
        for( $i = 0; $i < $this->zip->numFiles; $i++ ){
            $stat = $this->zip->statIndex($i);
            if (strpos($stat['name'], '.yw5') !== false) {
                $yWrite = $stat['name'];
                break;
            }
        }
        if (!$yWrite) {
            return;
        }

        $enc = new XmlEncoder();
        $yWrite = $enc->decode($this->zip->getFromName($yWrite), 'object');

        if (!array_key_exists('PROJECT', $yWrite) || !array_key_exists('Title', $yWrite['PROJECT'])) {
            return null;
        }

        $project = new Project();
        $project
            ->setUser($user)
            ->setTitle($yWrite['PROJECT']['Title'])
        ;

        if (array_key_exists('AuthorName', $yWrite['PROJECT'])) {
            $project->setAuthorsName($yWrite['PROJECT']['AuthorName']);
        }

        $this->projectRepository->save($project);

        if (array_key_exists('CHAPTERS', $yWrite)) {
            $this->importChapters($project, $yWrite);
        }

        return $project;
    }

    private function importChapters(Project $project, array $yWrite)
    {
        $chapters = $yWrite['CHAPTERS'];
        if (array_key_exists('ID', $chapters['CHAPTER'])) {
            $chapters['CHAPTER'] = [$chapters['CHAPTER']];
        }
        foreach ($chapters['CHAPTER'] as $rawChapter) {
            if (!array_key_exists('ID', $rawChapter)) {
                break;
            }
            $chapter = new Chapter();
            $chapter
                ->setProject($project)
                ->setTitle($rawChapter['Title'] ?? 'Unnamed chapter')
                ->setDescription($rawChapter['Desc'] ?? null)
                ->setPosition($project->getChapters()->count() + 1)
            ;

            $this->chapterRepository->save($chapter);
            $project->addChapter($chapter);

            $this->importScenes($chapter, $rawChapter['Scenes'], $yWrite);
        }
    }

    private function importScenes(Chapter $chapter, array $ids, array $yWrite)
    {
        $scId = $ids['ScID'];
        if (!is_array($scId)) {
            $scId = [$scId];
        }
        foreach ($scId as $id) {
            $this->importScene($chapter, $id, $yWrite);
        }
    }

    private function importScene(Chapter $chapter, string $id, array $yWrite) {
        $scenes = $yWrite['SCENES']['SCENE'] ?? null;
        if (!$scenes) {
            return;
        }
        if (array_key_exists('ID', $scenes)) {
            $scenes = [$scenes];
        }
        foreach ($scenes as $rawScene) {
            if ($rawScene['ID'] === $id) {
                $scene = new Scene();
                $scene
                    ->setChapter($chapter)
                    ->setPosition($chapter->getScenes()->count() + 1)
                    ->setTitle($rawScene['Title'] ?? null)
                    ->setDescription($rawScene['Desc'] ?? null)
                    ->setGoalType(Scene::GOAL_ACTION)
                    ->setGoal($rawScene['Goal'] ?? null)
                    ->setStatus($rawScene['Status'] ?? 1)
                    ->setConflict($rawScene['Conflict'] ?? null)
                    ->setOutcome($rawScene['Outcome'] ?? null)
                    ->setOutcome($rawScene['Notes'] ?? null)
                ;
                $content = $this->zip->getFromName('RTF5/' . $rawScene['RTFFile']);
                $document = new Document($content);
                $formatter = new HtmlFormatter();
                $scene->setContent(strip_tags($formatter->Format($document)));

                $this->sceneRepository->saveScene($scene);
                $chapter->addScene($scene);
            }
        }
    }
}
