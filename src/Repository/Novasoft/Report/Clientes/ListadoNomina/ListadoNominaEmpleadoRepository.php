<?php

namespace App\Repository\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaEmpleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListadoNominaEmpleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNominaEmpleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNominaEmpleado[]    findAll()
 * @method ListadoNominaEmpleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListadoNominaEmpleado::class);
    }

    /**
     * @param $identificacion
     * @return Criteria
     */
    public static function filterByIdentificacionCriteria($identificacion)
    {
        return Criteria::create()->where(Criteria::expr()->eq('identificacion', $identificacion))->setMaxResults(1);
    }
}
