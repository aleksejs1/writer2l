<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Project;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/location")
 */
class LocationEditController extends AbstractController
{
    /**
     * @Route("/{location}/edit", name="location_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project, Location $location): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_list', ['project' => $project->getId()]);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }
}
