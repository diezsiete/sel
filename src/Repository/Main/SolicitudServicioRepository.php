<?php

namespace App\Repository\Main;

use App\Entity\Main\SolicitudServicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SolicitudServicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method SolicitudServicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method SolicitudServicio[]    findAll()
 * @method SolicitudServicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SolicitudServicioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SolicitudServicio::class);
    }
}
