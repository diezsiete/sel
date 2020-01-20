<?php

namespace App\Repository\Novasoft\Report\Clientes\ListadoNomina;

use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNominaSubgrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListadoNominaSubgrupo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListadoNominaSubgrupo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListadoNominaSubgrupo[]    findAll()
 * @method ListadoNominaSubgrupo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListadoNominaSubGrupoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListadoNominaSubgrupo::class);
    }

    public static function filterByCodigoCriteria($codigo)
    {
        return Criteria::create()->where(Criteria::expr()->eq('codigo', $codigo))->setMaxResults(1);
    }
}
