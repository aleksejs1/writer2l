<?php

namespace App\Controller;

use App\Entity\Character;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/character")
 */
class CharacterDeleteController extends AbstractController
{
    /**
     * @Route("/{character}/delete", name="character_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Character $character): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $character->getProject());
        $project = $character->getProject();
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('character_list', ['project' => $project->getId()]);
    }
}
