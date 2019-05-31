<?php

namespace App\Repository;

use App\Entity\Empleado;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Empleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empleado[]    findAll()
 * @method Empleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Empleado::class);
    }

    /**
     * @param string $identificacion
     * @return Empleado|null
     */
    public function findByIdentificacion(string $identificacion)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.usuario', 'u')
            ->andWhere('u.identificacion = :identificacion')
            ->setParameter('identificacion', $identificacion);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Usuario[]
     */
    public function findAllUsuarios()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('u')
            ->from(Usuario::class, 'u')
            ->join($this->_entityName, 'e', 'WITH', 'u = e.usuario');
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Empleado[] Returns an array of Empleado objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Empleado
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
