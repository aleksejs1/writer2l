<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Project;
use App\Form\ChapterType;
use App\Repository\ChapterRepository;
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
     */
    public function delete(Request $request, Project $project, Chapter $chapter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chapter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_show', ['project' => $project->getId()]);
    }
}
