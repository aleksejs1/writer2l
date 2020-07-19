<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneShowController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/show", name="scene_show", methods={"GET"})
     * @param Scene $scene
     * @return Response
     */
    public function show(Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());

        return $this->render('scene/show.html.twig', [
            'scene' => $scene,
            'chapter' => $scene->getChapter(),
            'project' => $scene->getChapter()->getProject(),
        ]);
    }
}
