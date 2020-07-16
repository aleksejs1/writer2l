<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

class Repository extends ServiceEntityRepository
{
    protected $entityClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entityClass);
    }

    public function save($entity): void
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException($this->entityClass . ' expected');
        }

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function saveCollection(Collection $collection) {
        foreach ($collection as $entity) {
            if (!$entity instanceof $this->entityClass) {
                throw new \InvalidArgumentException($this->entityClass . ' expected');
            }

            $this->_em->persist($entity);
        }

        $this->_em->flush();
    }

    public function delete($entity): void
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException($this->entityClass . ' expected');
        }

        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
