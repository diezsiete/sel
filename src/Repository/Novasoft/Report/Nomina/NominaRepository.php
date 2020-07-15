<?php

namespace App\Repository\Novasoft\Report\Nomina;

use App\Entity\Novasoft\Report\Nomina\Nomina;
use App\Entity\Main\Usuario;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nomina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nomina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nomina[]    findAll()
 * @method Nomina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NominaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nomina::class);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param Usuario $usuario
     * @param DateTimeInterface $fecha
     * @return Nomina|null Returns an array of Nomina objects
     */
    public function findByFecha(Usuario $usuario, DateTimeInterface $fecha)
    {
        $from = new DateTime($fecha->format("Y-m-d")." 00:00:00");
        $to   = new DateTime($fecha->format("Y-m-d")." 23:59:59");

        return $this->createQueryBuilder('rn')
            ->where('rn.usuario = :usuario')
            ->setParameter('usuario', $usuario)
            ->andWhere('rn.fecha BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
