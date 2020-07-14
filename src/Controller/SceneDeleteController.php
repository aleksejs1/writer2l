<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Security\Voter\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project/{project}/chapter/{chapter}")
 */
class SceneDeleteController extends AbstractController
{
    /**
     * @Route("/scene/{scene}/delete", name="scene_delete", methods={"DELETE"})
     * @param Request $request
     * @param Scene $scene
     * @return Response
     */
    public function delete(Request $request, Scene $scene): Response
    {
        $this->denyAccessUnlessGranted(ProjectVoter::PROJECT_EDIT, $scene->getChapter()->getProject());
        $chapter = $scene->getChapter();
        if ($this->isCsrfTokenValid('delete'.$scene->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($scene);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_show_chapter', [
            'project' => $chapter->getProject()->getId(),
            'chapter' => $chapter->getId()
        ]);
    }
}
