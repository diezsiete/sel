<?php

namespace App\Repository\Evaluacion\Pregunta;

use App\Entity\Evaluacion\Pregunta\Opcion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Opcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opcion[]    findAll()
 * @method Opcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpcionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Opcion::class);
    }

    public static function getByOrderCriteria(int $orden)
    {
        return Criteria::create()->where(Criteria::expr()->eq('respuesta', $orden));
    }

    // /**
    //  * @return Opcion[] Returns an array of Opcion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Opcion
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
