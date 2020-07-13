<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProjectListController extends AbstractController
{
    /**
     * @Route("/project/list", name="project_list")
     */
    public function index()
    {
        return $this->render('project_list/index.html.twig', [
            'controller_name' => 'ProjectListController',
        ]);
    }
}
