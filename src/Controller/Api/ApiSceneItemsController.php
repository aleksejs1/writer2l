<?php

namespace App\Controller\Api;

use App\Entity\Scene;
use App\Repository\ItemRepository;
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
class ApiSceneItemsController extends AbstractController
{
    /**
     * @Route("scene/{scene}/items", name="api_scene_items", methods={"GET"})
     * @param SceneSaveService $sceneSaveService
     * @param Request $request
     * @param ItemRepository $itemRepository
     * @param Scene $scene
     * @return Response
     */
    public function characters(
        SceneSaveService $sceneSaveService,
        Request $request, ItemRepository
        $itemRepository, Scene $scene
    ): Response {
        if (!$this->isGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject())) {
            return new JsonResponse(['success' => false]);
        }

        $add = $request->get('add');
        if ($add) {
            $addItem = $itemRepository->find($add);
            if (!$scene->getItems()->contains($addItem)) {
                $scene->addItem($addItem);
                $sceneSaveService->save($scene);
            }
        }
        $remove = $request->get('remove');
        if ($remove) {
            $removeItem = $itemRepository->find($remove);
            if ($scene->getItems()->contains($removeItem)) {
                $scene->removeItem($removeItem);
                $sceneSaveService->save($scene);
            }
        }

        return new JsonResponse(['success' => true]);
    }
}
