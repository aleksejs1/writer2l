<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneEditController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/edit", name="scene_edit", methods={"GET","POST"})
     * @param SceneRepository $sceneRepository
     * @param Request $request
     * @param Scene $scene
     * @return Response
     */
    public function edit(SceneRepository $sceneRepository, Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
        $form = $this->createForm(SceneType::class, $scene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sceneRepository->save($scene);

            return $this->redirectToRoute('project_show_chapter', [
                'project' => $scene->getChapter()->getProject()->getId(),
                'chapter' => $scene->getChapter()->getId()
            ]);
        }

        return $this->render('scene/edit.html.twig', [
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
