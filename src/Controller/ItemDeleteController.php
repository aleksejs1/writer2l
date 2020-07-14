<?php

namespace App\Controller;

use App\Entity\Item;
use App\Security\Voter\ProjectVoter;
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
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function delete(Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $item->getProject());
        $project = $item->getProject();
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_list', ['project' => $project->getId()]);
    }
}
