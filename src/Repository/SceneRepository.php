<?php

namespace App\Repository;

use App\Entity\Scene;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scene|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scene|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scene[]    findAll()
 * @method Scene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SceneRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityClass = Scene::class;

        parent::__construct($registry);
    }

    public function save($entity): void
    {
        throw new \BadMethodCallException('Use SaveSceneService');
    }

    public function saveScene(Scene $scene)
    {
        $this->_em->persist($scene);
        $this->_em->flush();
    }
}
