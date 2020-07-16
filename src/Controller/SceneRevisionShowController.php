<?php

namespace App\Controller;

use App\Entity\SceneRevision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/scene/{scene}/revisions")
 */
class SceneRevisionShowController extends AbstractController
{
    /**
     * @Route("/{revision}", name="scene_revision_show", methods={"GET"})
     */
    public function show(SceneRevision $sceneRevision): Response
    {
        return $this->render('scene_revision/show.html.twig', [
            'sceneRevision' => $sceneRevision,
            'project' => $sceneRevision->getScene()->getChapter()->getProject(),
        ]);
    }
}
