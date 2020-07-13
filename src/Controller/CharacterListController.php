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
class CharacterListController extends AbstractController
{
    /**
     * @Route("/", name="character_list", methods={"GET"})
     */
    public function index(Project $project): Response
    {
        return $this->render('character/index.html.twig', [
            'characters' => $project->getCharacters(),
            'project' => $project,
        ]);
    }
}
