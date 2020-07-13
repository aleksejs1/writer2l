<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use App\Form\ChapterType;
use App\Repository\ChapterRepository;
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
     * @return Response
     */
    public function new(Request $request, Project $project): Response
    {
        $newChapterSequence = $project->getChapters()->count() + 1;
        $chapter = new Chapter();
        $chapter
            ->setProject($project)
            ->setTitle('Chapter ' . $newChapterSequence)
        ;
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chapter);
            $entityManager->flush();

            return $this->redirectToRoute('project_show', ['project' => $project->getId()]);
        }

        return $this->render('chapter/new.html.twig', [
            'chapter' => $chapter,
            'form' => $form->createView(),
        ]);
    }
}
