<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter")
 */
class ChapterDeleteController extends AbstractController
{
    /**
     * @Route("/{chapter}/delete", name="chapter_delete", methods={"DELETE"})
     * @param Request $request
     * @param Chapter $chapter
     * @return Response
     */
    public function delete(Request $request, Chapter $chapter): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());
        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chapter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_show', ['project' => $chapter->getProject()->getId()]);
    }
}
