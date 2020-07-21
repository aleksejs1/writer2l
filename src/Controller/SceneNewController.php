<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Security\Voter\ProjectVoter;
use App\Service\SceneSaveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneNewController extends AbstractController
{
    /**
     * @Route("/new", name="scene_new", methods={"GET","POST"})
     * @param SceneSaveService $sceneSaveService
     * @param TranslatorInterface $translator
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function new(
        SceneSaveService $sceneSaveService,
        TranslatorInterface $translator,
        Chapter $chapter
    ): Response {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());
        $scene = new Scene();
        $scene
            ->setChapter($chapter)
            ->setTitle($translator->trans('New Scene'))
            ->setPosition($chapter->getScenes()->count())
            ->setStatus(Scene::STATUS_OUTLINE)
            ->setGoalType(Scene::GOAL_ACTION)
        ;
        $sceneSaveService->save($scene);

        return $this->redirectToRoute('scene_edit', [
            'project' => $chapter->getProject()->getId(),
            'chapter' => $chapter->getId(),
            'scene' => $scene->getId(),
        ]);
    }
}
