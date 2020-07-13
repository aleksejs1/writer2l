<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Project;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/character")
 */
class CharacterEditController extends AbstractController
{
    /**
     * @Route("/{character}/edit", name="character_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project, Character $character): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('character_list', ['project' => $project->getId()]);
        }

        return $this->render('character/edit.html.twig', [
            'character' => $character,
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }
}
