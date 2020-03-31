<?php

namespace App\Repository\Napi\ServicioEmpleados;

use App\Entity\Napi\ServicioEmpleados\CertificadoIngresos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CertificadoIngresos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificadoIngresos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificadoIngresos[]    findAll()
 * @method CertificadoIngresos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificadoIngresosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CertificadoIngresos::class);
    }
}
