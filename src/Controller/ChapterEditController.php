<?php

namespace App\Controller;

use App\Entity\Chapter;
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
class ChapterEditController extends AbstractController
{
    /**
     * @Route("/{chapter}/edit", name="chapter_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function edit(Request $request, Chapter $chapter): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getRepository(Chapter::class)->save($chapter);

            return $this->redirectToRoute('project_show_chapter', [
                'project' => $chapter->getProject()->getId(),
                'chapter' => $chapter->getId()
            ]);
        }

        return $this->render('chapter/edit.html.twig', [
            'chapter' => $chapter,
            'project' => $chapter->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
