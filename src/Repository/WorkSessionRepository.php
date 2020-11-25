<?php

namespace App\Repository;

use App\Entity\Scene;
use App\Entity\User;
use App\Entity\WorkSession;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkSession[]    findAll()
 * @method WorkSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkSessionRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityClass = WorkSession::class;

        parent::__construct($registry);
    }

    public function fetchCurrent(User $user, Scene $scene, \DateTime $datetime)
    {
        return $this
            ->createQueryBuilder('ws')
            ->where('ws.user = :user')
            ->setParameter('user', $user)
            ->andWhere('ws.scene = :scene')
            ->setParameter('scene', $scene)
            ->andWhere('ws.end > :datetime')
            ->setParameter('datetime', $datetime)
            ->orderBy('ws.end', Criteria::DESC)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
