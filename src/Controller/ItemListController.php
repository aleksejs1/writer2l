<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/item")
 */
class ItemListController extends AbstractController
{
    /**
     * @Route("/", name="item_list", methods={"GET"})
     * @param Project $project
     * @return Response
     */
    public function index(Project $project): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $project->getItems(),
            'project' => $project,
        ]);
    }
}
