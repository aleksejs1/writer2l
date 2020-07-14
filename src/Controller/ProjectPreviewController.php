<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectPreviewController extends AbstractController
{
    /**
     * @Route("/{project}/preview", name="project_preview", methods={"GET"})
     */
    public function index(Project $project): Response
    {
        return $this->render('project/preview.html.twig', [
            'project' => $project,
        ]);
    }
}
