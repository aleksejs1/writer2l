<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/scene")
 */
class SceneController extends AbstractController
{
    /**
     * @Route("/", name="scene_index", methods={"GET"})
     */
    public function index(SceneRepository $sceneRepository): Response
    {
        return $this->render('scene/index.html.twig', [
            'scenes' => $sceneRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="scene_show", methods={"GET"})
     */
    public function show(Scene $scene): Response
    {
        return $this->render('scene/show.html.twig', [
            'scene' => $scene,
        ]);
    }
}
