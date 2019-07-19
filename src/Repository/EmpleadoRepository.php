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
     * @param string|string[] $identificacion
     * @return Empleado|null|Empleado[]
     */
    public function findByIdentificacion($identificacion)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.usuario', 'u');

        if(is_array($identificacion)) {
            $qb->andWhere($qb->expr()->in('u.identificacion', ':identificacion'));
        }else{
            $qb->andWhere('u.identificacion = :identificacion');
        }

        $query = $qb->setParameter('identificacion', $identificacion)
            ->getQuery();

        return is_array($identificacion) ? $query->getResult() : $query->getOneOrNullResult();
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

    /**
     * @param string|string[] $codigoConvenio
     * @return Empleado[]
     */
    public function findByConvenio($codigoConvenio)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.convenio', 'c');
        if(is_array($codigoConvenio)) {
            $qb->andWhere($qb->expr()->in('c.codigo', ':codigoConvenio'));
        } else {
            $qb->andWhere('c.codigo = :codigoConvenio');
        }
        return $qb->setParameter('codigoConvenio', $codigoConvenio)
            ->getQuery()
            ->getResult();
    }

    public function findBySsrsDb($ssrsDb)
    {
        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.ssrsDb', ':ssrsDb')
            ->setParameter('ssrsDb', $ssrsDb);
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
