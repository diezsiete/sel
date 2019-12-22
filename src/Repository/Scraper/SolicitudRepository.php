<?php

namespace App\Repository\Scraper;

use App\Entity\Scraper\Solicitud;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Solicitud|null find($id, $lockMode = null, $lockVersion = null)
 * @method Solicitud|null findOneBy(array $criteria, array $orderBy = null)
 * @method Solicitud[]    findAll()
 * @method Solicitud[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudRepository extends ServiceEntityRepository
{
    const SIN_EJECUTAR = 0;
    const EJECUTANDO = 1;
    const EJECUTADO_EXITO = 2;
    const EJECUTADO_ERROR = 3;
    const ESPERANDO_EN_COLA = 4;
    const TERMINADO_ABRUPTO = 5;

    public function getEstadoString($estado) {
        switch($estado) {
            case static::SIN_EJECUTAR:
                return 'SIN EJECUTAR';
            case static::EJECUTANDO:
                return 'EJECUTANDO';
            case static::EJECUTADO_EXITO:
                return 'EJECUTADO EXITO';
            case static::ESPERANDO_EN_COLA:
                return 'ESPERANDO EN COLA';
            case static::TERMINADO_ABRUPTO:
                return 'TERMINADO ABRUPTO';
            default:
                return 'EJECUTADO ERROR';
        }
    }

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Solicitud::class);
    }



    // /**
    //  * @return Solicitud[] Returns an array of Solicitud objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Solicitud
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
