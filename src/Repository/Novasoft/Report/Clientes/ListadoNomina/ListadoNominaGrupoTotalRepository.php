<?php

namespace App\Repository\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupoTotal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListadoNominaGrupoTotal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNominaGrupoTotal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNominaGrupoTotal[]    findAll()
 * @method ListadoNominaGrupoTotal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaGrupoTotalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListadoNominaGrupoTotal::class);
    }

    public static function filterByIdentCriteria($value)
    {
        return Criteria::create()->where(Criteria::expr()->eq('identificacion', $value))->setMaxResults(1);
    }

}
