<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Repository\ChapterRepository;
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
     * @param ChapterRepository $chapterRepository
     * @return Response
     */
    public function delete(Request $request, Chapter $chapter, ChapterRepository $chapterRepository): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());
        if ($this->isCsrfTokenValid('delete'.$chapter->getId(), $request->request->get('_token'))) {
            $chapterRepository->delete($chapter);
        }

        return $this->redirectToRoute('project_show', ['project' => $chapter->getProject()->getId()]);
    }
}
