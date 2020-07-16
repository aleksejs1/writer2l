<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use App\Form\ChapterType;
use App\Repository\ChapterRepository;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter")
 */
class ChapterNewController extends AbstractController
{
    /**
     * @Route("/new", name="chapter_new", methods={"GET","POST"})
     * @param Request $request
     * @param Project $project
     * @param ChapterRepository $chapterRepository
     * @return Response
     */
    public function new(Request $request, Project $project, ChapterRepository $chapterRepository): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);
        $newChapterSequence = $project->getChapters()->count() + 1;
        $chapter = new Chapter();
        $chapter
            ->setProject($project)
            ->setTitle('Chapter ' . $newChapterSequence)
            ->setPosition($newChapterSequence)
        ;
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chapterRepository->save($chapter);

            return $this->redirectToRoute('project_show', ['project' => $project->getId()]);
        }

        return $this->render('chapter/new.html.twig', [
            'chapter' => $chapter,
            'new' => true,
            'project' => $chapter->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
