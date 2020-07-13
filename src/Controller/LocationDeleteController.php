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
class LocationDeleteController extends AbstractController
{
    /**
     * @Route("/{location}/delete", name="location_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project, Location $location): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('location_list', ['project' => $project->getId()]);
    }
}
