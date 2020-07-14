<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Repository\LocationRepository;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneLocationsController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/locations", name="scene_locations", methods={"GET","POST"})
     * @param Request $request
     * @param LocationRepository $locationRepository
     * @param Scene $scene
     * @return Response
     */
    public function characters(Request $request, LocationRepository $locationRepository, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
        $add = $request->get('add');
        if ($add) {
            $addLocation = $locationRepository->find($add);
            if (!$scene->getLocations()->contains($addLocation)) {
                $scene->addLocation($addLocation);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        $remove = $request->get('remove');
        if ($remove) {
            $removeLocation = $locationRepository->find($remove);
            if ($scene->getLocations()->contains($removeLocation)) {
                $scene->removeLocation($removeLocation);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        $locationsOut = [];
        foreach ($scene->getChapter()->getProject()->getLocations() as $location) {
            if (!$scene->getLocations()->contains($location)) {
                $locationsOut[] = $location;
            }
        }

        return $this->render('scene/locations.html.twig', [
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
            'locationsIn' => $scene->getLocations(),
            'locationsOut' => $locationsOut,
        ]);
    }
}
