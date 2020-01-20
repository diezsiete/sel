<?php

namespace App\Repository\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaRenglon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListadoNominaRenglon|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNominaRenglon|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNominaRenglon[]    findAll()
 * @method ListadoNominaRenglon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaRenglonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListadoNominaRenglon::class);
    }

    // /**
    //  * @return ListadoNominaRenglon[] Returns an array of ListadoNominaRenglon objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListadoNominaRenglon
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
