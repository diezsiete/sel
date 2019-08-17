<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param null|string|Tag $search
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function searchQueryBuilder($search = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');

        if($search) {
            if(is_string($search)) {
                $qb->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.title', ':search'),
                        $qb->expr()->like('p.abstract', ':search')
                    )
                )->setParameter('search', '%' . $search . '%');
            }
            else if($search instanceof Tag) {
                $qb->join('p.tags', 't')
                    ->andWhere($qb->expr()->eq('t', ':tag'))
                    ->setParameter('tag', $search);
            }
        }

        return $qb;
    }

    /**
     * @return Post[]
     */
    public function findRandom($maxResults = 3, ?Post $exceptPost = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults($maxResults);
        if($exceptPost) {
            $qb->where($qb->expr()->neq('p.id', $exceptPost->getId()));
        }
        return $qb
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
