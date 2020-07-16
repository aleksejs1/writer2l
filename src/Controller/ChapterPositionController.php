<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Repository\ChapterRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\SortSortableCollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter")
 */
class ChapterPositionController extends AbstractController
{
    /**
     * @Route("/{chapter}/position/{newPosition}", name="chapter_position", methods={"GET"})
     * @param SortSortableCollectionService $sortableCollectionService
     * @param ChapterRepository $chapterRepository
     * @param Chapter $chapter
     * @param int $newPosition
     * @return Response
     */
    public function change(
        SortSortableCollectionService $sortableCollectionService,
        ChapterRepository $chapterRepository,
        Chapter $chapter,
        int $newPosition
    ): Response {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $chapter->getProject());

        $chapters = $chapter->getProject()->getChapters();
        $sortableCollectionService->updatePositions($chapter, $chapters, $newPosition);
        $chapterRepository->saveCollection($chapters);

        return $this->redirectToRoute('project_show_chapter', [
            'project' => $chapter->getProject()->getId(),
            'chapter' => $chapter->getId()
        ]);
    }
}
