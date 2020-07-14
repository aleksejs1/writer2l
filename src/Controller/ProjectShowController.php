<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}")
 */
class ProjectShowController extends AbstractController
{
    /**
     * @Route("/", name="project_show", methods={"GET"}, defaults={"chapter": null})
     * @Route("/chapter/{chapter}", name="project_show_chapter", methods={"GET"})
     */
    public function show(Request $request, Project $project, ?Chapter $chapter): Response
    {
        if ($request->attributes->get('_route_params')['chapter'] === null) {
            $chapter = null;
        }

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'chapter' => $chapter,
        ]);
    }
}
