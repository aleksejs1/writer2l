<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Repository\SceneRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\SortSortableCollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class ScenePositionController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/position/{newPosition}", name="scene_position", methods={"GET"})
     * @param SortSortableCollectionService $sortableCollectionService
     * @param SceneRepository $sceneRepository
     * @param Scene $scene
     * @param int $newPosition
     * @return Response
     */
    public function change(
        SortSortableCollectionService $sortableCollectionService,
        SceneRepository $sceneRepository,
        Scene $scene,
        int $newPosition
    ): Response {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());

        $scenes = $scene->getChapter()->getScenes();
        $sortableCollectionService->updatePositions($scene, $scenes, $newPosition);
        $sceneRepository->saveCollection($scenes);

        return $this->redirectToRoute('project_show_chapter', [
            'project' => $scene->getChapter()->getProject()->getId(),
            'chapter' => $scene->getChapter()->getId()
        ]);
    }
}
