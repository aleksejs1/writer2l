<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
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
     */
    public function new(Request $request, Chapter $chapter): Response
    {
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
