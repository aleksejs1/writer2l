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
class CharacterDeleteController extends AbstractController
{
    /**
     * @Route("/{character}/delete", name="character_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project, Character $character): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('character_list', ['project' => $project->getId()]);
    }
}
