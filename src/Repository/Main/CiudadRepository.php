<?php

namespace App\Repository\Main;

use App\Entity\Main\Ciudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ciudad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ciudad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ciudad[]    findAll()
 * @method Ciudad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiudadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ciudad::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findColombiaQuery()
    {
        return $this->createQueryBuilder('c')
            ->join('c.pais', 'p')
            ->andWhere("p.nombre = 'COLOMBIA'");
    }
    /**
     * @return Ciudad[]
     */
    public function findColmbia()
    {
        return $this->findColombiaQuery()->getQuery()->getResult();
    }

    public function ciudadesColombiaCriteria()
    {
        return Criteria::create()->andWhere(Criteria::expr()->eq('c.pais.nombre', 'COLOMBIA'));
    }

    /**
     * @param string $ciudadNombre
     * @param null|string $dptoNombre
     * @param null|string $paisNombre
     * @return Ciudad[]
     */
    public function findByNombre($ciudadNombre, $dptoNombre = null, $paisNombre = 'COLOMBIA')
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.dpto', 'dpto')
            ->join('c.pais', 'pais');

        $qb->where($qb->expr()->eq('c.nombre', ':ciudadNombre'))
            ->setParameter('ciudadNombre', $ciudadNombre);
        if($dptoNombre) {
            $qb->andWhere($qb->expr()->eq('dpto.nombre', ':dptoNombre'))
                ->setParameter('dptoNombre', $dptoNombre);
        }
        if($paisNombre) {
            $qb->andWhere($qb->expr()->eq('pais.nombre', ':paisNombre'))
                ->setParameter('paisNombre', $paisNombre);
        }
        return $qb->getQuery()->getResult();
    }
}
