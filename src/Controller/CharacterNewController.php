<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Project;
use App\Form\CharacterType;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/project/{project}/character")
 */
class CharacterNewController extends AbstractController
{
    /**
     * @Route("/new", name="character_new", methods={"GET","POST"})
     * @param Request $request
     * @param Project $project
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function new(Request $request, Project $project, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);
        $character = new Character();
        $character
            ->setProject($project)
            ->setShortName($translator->trans('New Character'))
        ;
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($character);
            $entityManager->flush();

            return $this->redirectToRoute('character_list', ['project' => $project->getId()]);
        }

        return $this->render('character/new.html.twig', [
            'character' => $character,
            'project' => $project,
            'new' => true,
            'form' => $form->createView(),
        ]);
    }
}
