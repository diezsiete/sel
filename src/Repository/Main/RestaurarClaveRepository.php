<?php

namespace App\Repository\Main;

use App\Entity\Main\RestaurarClave;
use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurarClave|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurarClave|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurarClave[]    findAll()
 * @method RestaurarClave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurarClaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurarClave::class);
    }

    /**
     * @return \App\Entity\Main\RestaurarClave|null
     */
    public function findByUsuario(Usuario $usuario)
    {
        try {
            return $this->createQueryBuilder('r')
                ->andWhere('r.usuario = :usuario')
                ->setParameter('usuario', $usuario)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param $id
     * @param $token
     * @return RestaurarClave|null
     */
    public function findByUrl($id, $token)
    {
        $token = urldecode($token);
        try {
            return $this->createQueryBuilder('r')
                ->join('r.usuario', 'u')
                ->andWhere('u.id = :id')
                ->andWhere('r.token = :token')
                ->andWhere('r.restaurada = false')
                ->setParameter('id', $id)
                ->setParameter('token', $token)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }


    /*
    public function findOneBySomeField($value): ?RestaurarClave
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
