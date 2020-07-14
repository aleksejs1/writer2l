<?php

namespace App\Controller;

use App\Entity\Project;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/location")
 */
class LocationListController extends AbstractController
{
    /**
     * @Route("/", name="location_list", methods={"GET"})
     * @param Project $project
     * @return Response
     */
    public function index(Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);

        return $this->render('location/index.html.twig', [
            'locations' => $project->getLocations(),
            'project' => $project,
        ]);
    }
}
