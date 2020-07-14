<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\ChapterRepository;
use App\Repository\CharacterRepository;
use App\Repository\SceneRepository;
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
     */
    public function characters(Request $request, CharacterRepository $chapterRepository, Scene $scene): Response
    {
        $add = $request->get('add');
        if ($add) {
            $addCharacter = $chapterRepository->find($add);
            if (!$scene->getCharacters()->contains($addCharacter)) {
                $scene->addCharacter($addCharacter);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        $remove = $request->get('remove');
        if ($remove) {
            $removeCharacter = $chapterRepository->find($remove);
            if ($scene->getCharacters()->contains($removeCharacter)) {
                $scene->removeCharacter($removeCharacter);
                $this->getDoctrine()->getManager()->flush();
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
