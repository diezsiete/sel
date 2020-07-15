<?php

namespace App\Repository\Main;

use App\Entity\Main\Representante;
use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Representante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Representante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Representante[]    findAll()
 * @method Representante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepresentanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Representante::class);
    }

    public static function encargadoCriteria()
    {
        return Criteria::create()->where(Criteria::expr()->eq('encargado', true));
    }

    public static function bccCriteria()
    {
        return Criteria::create()->where(Criteria::expr()->eq('bcc', true));
    }

    /**
     * @param \App\Entity\Main\Usuario $usuario
     * @return Representante|null
     * @throws NonUniqueResultException
     */
    public function findByUsuario(Usuario $usuario)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.usuario = :usuario')
            ->setParameter('usuario', $usuario)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
