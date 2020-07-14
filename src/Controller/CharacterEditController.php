<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Security\Voter\ProjectVoter;
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
     * @param Request $request
     * @param Character $character
     * @return Response
     */
    public function edit(Request $request, Character $character): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $character->getProject());
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('character_list', ['project' => $character->getProject()->getId()]);
        }

        return $this->render('character/edit.html.twig', [
            'character' => $character,
            'project' => $character->getProject(),
            'form' => $form->createView(),
        ]);
    }
}
