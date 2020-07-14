<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\ChapterRepository;
use App\Repository\CharacterRepository;
use App\Repository\LocationRepository;
use App\Repository\SceneRepository;
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
     */
    public function characters(Request $request, LocationRepository $locationRepository, Scene $scene): Response
    {
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
