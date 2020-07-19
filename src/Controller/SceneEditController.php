<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Form\SceneType;
use App\Security\Voter\ProjectVoter;
use App\Service\SceneSaveService;
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
     * @param SceneSaveService $sceneSaveService
     * @param Request $request
     * @param Scene $scene
     * @return Response
     */
    public function edit(SceneSaveService $sceneSaveService, Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
        $form = $this->createForm(SceneType::class, $scene, ['project' => $scene->getChapter()->getProject()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sceneSaveService->save($scene);

            return $this->redirectToRoute('scene_show', [
                'project' => $scene->getChapter()->getProject()->getId(),
                'chapter' => $scene->getChapter()->getId(),
                'scene' => $scene->getId(),
            ]);
        }

        return $this->render('scene/edit.html.twig', [
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
