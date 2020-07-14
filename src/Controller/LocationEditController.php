<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Security\Voter\ProjectVoter;
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
     * @param Request $request
     * @param Location $location
     * @return Response
     */
    public function edit(Request $request, Location $location): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $location->getProject());
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('location_list', ['project' => $location->getProject()->getId()]);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'project' => $location->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
