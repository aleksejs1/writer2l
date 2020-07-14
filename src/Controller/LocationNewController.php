<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Project;
use App\Form\LocationType;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/location")
 */
class LocationNewController extends AbstractController
{
    /**
     * @Route("/new", name="location_new", methods={"GET","POST"})
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function new(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);
        $location = new Location();
        $location
            ->setProject($project)
            ->setTitle('New Location')
        ;
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('location_list', ['project' => $project->getId()]);
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'new' => true,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
