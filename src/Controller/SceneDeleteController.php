<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
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
     */
    public function delete(Request $request, Chapter $chapter, Scene $scene): Response
    {
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
