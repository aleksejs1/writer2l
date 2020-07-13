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
class ItemEditController extends AbstractController
{
    /**
     * @Route("/{item}/edit", name="item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_list', ['project' => $project->getId()]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }
}
