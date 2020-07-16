<?php

namespace App\Controller;

use App\Entity\Scene;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/scene/{scene}/revisions")
 */
class SceneRevisionListController extends AbstractController
{
    /**
     * @Route("/", name="scene_revision_index", methods={"GET"})
     */
    public function index(Scene $scene): Response
    {
        return $this->render('scene_revision/index.html.twig', [
            'sceneRevisions' => $scene->getSceneRevisions(),
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
        ]);
    }
}
