<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Project;
use App\Form\ItemType;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/item")
 */
class ItemNewController extends AbstractController
{
    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function new(Request $request, Project $project): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);
        $item = new Item();
        $item
            ->setProject($project)
            ->setTitle('New Item')
        ;
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_list', ['project' => $project->getId()]);
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'new' => true,
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
