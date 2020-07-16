<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityClass = Chapter::class;

        parent::__construct($registry);
    }

}
