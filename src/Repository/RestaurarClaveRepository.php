<?php

namespace App\Repository;

use App\Entity\RestaurarClave;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RestaurarClave|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurarClave|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurarClave[]    findAll()
 * @method RestaurarClave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurarClaveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RestaurarClave::class);
    }

    /**
     * @return RestaurarClave|null
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
