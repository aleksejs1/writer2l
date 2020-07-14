<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Security\Voter\ProjectVoter;
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
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function edit(Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $item->getProject());
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_list', ['project' => $item->getProject()->getId()]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'project' => $item->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
