<?php

namespace App\Repository;

use App\Entity\Ciudad;
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
     * @return Ciudad[]
     */
    public function findColmbia()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.pais = :colombia')
            ->setParameter('colombia', '1')
            ->getQuery()
            ->getResult();
    }

    public function ciudadesColombiaCriteria()
    {
        return Criteria::create()->andWhere(Criteria::expr()->eq('c.pais', '1'));
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
