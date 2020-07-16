<?php

namespace App\Controller;

use App\Entity\Project;
use App\Security\Voter\ProjectVoter;
use Mpdf\Mpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectExportPdfController extends AbstractController
{
    /**
     * @Route("/{project}/export/pdf", name="project_export", methods={"GET"})
     * @param Project $project
     */
    public function index(Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $project);
        $book = $this->renderView('project/export.html.twig', [
            'project' => $project,
        ]);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($book);
        $mpdf->Output();
    }
}
