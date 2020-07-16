<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Repository\CharacterRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\SceneSaveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneCharactersController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/characters", name="scene_characters", methods={"GET","POST"})
     * @param SceneSaveService $sceneSaveService
     * @param Request $request
     * @param CharacterRepository $chapterRepository
     * @param Scene $scene
     * @return Response
     */
    public function characters(SceneSaveService $sceneSaveService, Request $request, CharacterRepository $chapterRepository, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
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
                $sceneRepository->save($scene);
            }
        }

        $charactersOut = [];
        foreach ($scene->getChapter()->getProject()->getCharacters() as $character) {
            if (!$scene->getCharacters()->contains($character)) {
                $charactersOut[] = $character;
            }
        }

        return $this->render('scene/characters.html.twig', [
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
            'charactersIn' => $scene->getCharacters(),
            'charactersOut' => $charactersOut,
        ]);
    }
}
