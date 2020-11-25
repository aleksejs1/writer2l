<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Scene;
use App\Service\WorkSessionService;

final class SceneDataPersister implements ContextAwareDataPersisterInterface
{
    private $workSessionService;

    public function __construct(WorkSessionService $workSessionService)
    {
        $this->workSessionService = $workSessionService;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Scene;
    }

    public function persist($data, array $context = [])
    {
        $this->workSessionService->ping($data->getChapter()->getProject()->getUser(), $data);
    }

    public function remove($data, array $context = [])
    {

    }
}
