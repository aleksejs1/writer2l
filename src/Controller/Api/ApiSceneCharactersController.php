<?php

namespace App\Controller\Api;

use App\Entity\Scene;
use App\Repository\CharacterRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\SceneSaveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/project/")
 */
class ApiSceneCharactersController extends AbstractController
{
    /**
     * @Route("scene/{scene}/characters", name="api_scene_characters", methods={"GET"})
     * @param SceneSaveService $sceneSaveService
     * @param Request $request
     * @param CharacterRepository $chapterRepository
     * @param Scene $scene
     * @return Response
     */
    public function characters(
        SceneSaveService $sceneSaveService,
        Request $request,
        CharacterRepository $chapterRepository,
        Scene $scene
    ): Response {
        if (!$this->isGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject())) {
            return new JsonResponse(['success' => false]);
        }

        $add = $request->get('add');
        if ($add) {
            $addCharacter = $chapterRepository->find($add);
            if (!$scene->getCharacters()->contains($addCharacter)) {
                $scene->addCharacter($addCharacter);
                $sceneSaveService->save($scene);
            }
        }
        $remove = $request->get('remove');
        if ($remove) {
            $removeCharacter = $chapterRepository->find($remove);
            if ($scene->getCharacters()->contains($removeCharacter)) {
                $scene->removeCharacter($removeCharacter);
                $sceneSaveService->save($scene);
            }
        }

        return new JsonResponse(['success' => true]);
    }
}
