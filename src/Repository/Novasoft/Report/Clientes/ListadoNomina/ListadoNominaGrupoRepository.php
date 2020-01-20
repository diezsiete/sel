<?php

namespace App\Repository\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListadoNominaGrupo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNominaGrupo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNominaGrupo[]    findAll()
 * @method ListadoNominaGrupo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaGrupoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListadoNominaGrupo::class);
    }

    public static function filterByNombreCriteria($nombre)
    {
        return Criteria::create()->where(Criteria::expr()->eq('nombre', $nombre))->setMaxResults(1);
    }
}
