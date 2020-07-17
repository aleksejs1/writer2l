<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectCollectionDataProvider  implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $projectRepository;
    private $tokenStorage;

    public function __construct(ProjectRepository $projectRepository, TokenStorageInterface $tokenStorage)
    {
        $this->projectRepository = $projectRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Project::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();
        if (!$user || !$user instanceof User) {
            return;
        }

        foreach ($this->projectRepository->findBy(['user' => $user]) as $project) {
            yield $project;
        }
    }
}
