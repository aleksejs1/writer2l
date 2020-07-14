<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Repository\ItemRepository;
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
     */
    public function characters(Request $request, ItemRepository $itemRepository, Scene $scene): Response
    {
        $add = $request->get('add');
        if ($add) {
            $addItem = $itemRepository->find($add);
            if (!$scene->getItems()->contains($addItem)) {
                $scene->addItem($addItem);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        $remove = $request->get('remove');
        if ($remove) {
            $removeItem = $itemRepository->find($remove);
            if ($scene->getItems()->contains($removeItem)) {
                $scene->removeItem($removeItem);
                $this->getDoctrine()->getManager()->flush();
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
