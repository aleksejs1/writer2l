<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Project;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/item")
 */
class ItemDeleteController extends AbstractController
{
    /**
     * @Route("/{item}/delete", name="item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_list', ['project' => $project->getId()]);
    }
}
