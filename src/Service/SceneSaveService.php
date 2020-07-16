<?php

namespace App\Service;

use App\Entity\Scene;
use App\Entity\SceneRevision;
use App\Repository\SceneRepository;
use App\Repository\SceneRevisionRepository;

class SceneSaveService
{
    private $sceneRepository;
    private $sceneRevisionRepository;

    public function __construct(SceneRepository $sceneRepository, SceneRevisionRepository $sceneRevisionRepository)
    {
        $this->sceneRepository = $sceneRepository;
        $this->sceneRevisionRepository = $sceneRevisionRepository;
    }

    public function save(Scene $scene): void
    {
        $lastRevision = $this->sceneRevisionRepository->findBy(['scene' => $scene], ['version' => 'DESC'], 1);
        if ($lastRevision) {
            $lastRevision = reset($lastRevision);
        }

        if (!$lastRevision || ($lastRevision && $lastRevision->getContent() !== $scene->getContent())) {
            $sceneRevision = new SceneRevision();
            $sceneRevision
                ->setContent($scene->getContent())
                ->setScene($scene)
            ;
            $scene->addSceneRevision($sceneRevision);
        }

        $this->sceneRepository->saveScene($scene);
    }
}
