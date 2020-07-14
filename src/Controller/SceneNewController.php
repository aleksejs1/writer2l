<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneNewController extends AbstractController
{
    /**
     * @Route("/new", name="scene_new", methods={"GET","POST"})
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function new(Request $request, Chapter $chapter): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());
        $scene = new Scene();
        $scene
            ->setChapter($chapter)
            ->setTitle('New Scene')
        ;

        $form = $this->createForm(SceneType::class, $scene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($scene);
            $entityManager->flush();

            return $this->redirectToRoute('project_show_chapter', [
                'project' => $chapter->getProject()->getId(),
                'chapter' => $chapter->getId()
            ]);
        }

        return $this->render('scene/new.html.twig', [
            'scene' => $scene,
            'project' => $chapter->getProject(),
            'form' => $form->createView(),
            'new' => true,
        ]);
    }
}
