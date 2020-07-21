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
        if ($this->needNewRevision($scene)) {
            $sceneRevision = new SceneRevision();
            $sceneRevision
                ->setContent($scene->getContent())
                ->setScene($scene)
            ;
            $scene->addSceneRevision($sceneRevision);
        }

        $this->sceneRepository->saveScene($scene);
    }

    private function needNewRevision(Scene $scene)
    {
        $lastRevision = $this->sceneRevisionRepository->findBy(['scene' => $scene], ['version' => 'DESC'], 1);
        if (!$lastRevision) {
            return true;
        }

        $lastRevision = reset($lastRevision);

        if ($lastRevision->getContent() === $scene->getContent()) {
            return false;
        }

        $limit = (new \DateTime())->sub(new \DateInterval('PT1H'));
        if ($lastRevision->getVersion() < $limit) {
            return true;
        }

        return false;
    }
}
