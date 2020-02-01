<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\Archivo;
use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Archivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivo[]    findAll()
 * @method Archivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Archivo::class);
    }

    /**
     * @param Usuario $usuario
     * @return Archivo[]
     */
    public function findAllByOwner(Usuario $usuario)
    {
        return $this->createQueryBuilder('a')
            ->join('a.owner', 'owner')
            ->where('owner = :usuario')
            ->setParameter('usuario', $usuario)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
