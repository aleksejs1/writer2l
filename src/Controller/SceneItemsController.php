<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Repository\ItemRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\SceneSaveService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneItemsController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/items", name="scene_items", methods={"GET","POST"})
     * @param SceneSaveService $sceneSaveService
     * @param Request $request
     * @param ItemRepository $itemRepository
     * @param Scene $scene
     * @return Response
     */
    public function characters(SceneSaveService $sceneSaveService, Request $request, ItemRepository $itemRepository, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
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

        $itemsOut = [];
        foreach ($scene->getChapter()->getProject()->getItems() as $item) {
            if (!$scene->getItems()->contains($item)) {
                $itemsOut[] = $item;
            }
        }

        return $this->render('scene/items.html.twig', [
            'scene' => $scene,
            'project' => $scene->getChapter()->getProject(),
            'itemsIn' => $scene->getItems(),
            'itemsOut' => $itemsOut,
        ]);
    }
}
