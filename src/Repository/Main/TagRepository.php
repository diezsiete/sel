<?php

namespace App\Repository\Main;

use App\Entity\Main\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findAllOrderBySize()
    {
        return $this->createQueryBuilder('t')
            ->join('t.post', 'p')
            ->select('t, COUNT(p) AS HIDDEN c')
            ->groupBy('t.id')
            ->orderBy('c', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
