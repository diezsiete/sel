<?php

namespace App\Repository\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Respuesta\Respuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Respuesta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Respuesta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Respuesta[]    findAll()
 * @method Respuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RespuestaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Respuesta::class);
    }

    public static function getByPreguntaCriteria($pregunta)
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('pregunta', $pregunta)
        );
    }

    // /**
    //  * @return Respuesta[] Returns an array of Respuesta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Respuesta
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
