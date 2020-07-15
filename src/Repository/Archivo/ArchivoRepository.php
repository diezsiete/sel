<?php

namespace App\Repository\Archivo;

use App\Entity\Archivo\Archivo;
use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Archivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archivo[]    findAll()
 * @method Archivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archivo::class);
    }

    /**
     * @param Usuario|string $usuario
     * @return Archivo[]
     */
    public function findAllByOwner($usuario)
    {
        return $this->createQueryBuilder('a')
            ->join('a.owner', 'owner')
            ->where('owner = :usuario')
            ->setParameter('usuario', $usuario)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findUsuariosByConvenioWithArchivos(Convenio $convenio)
    {
        return $this->_em->createQueryBuilder()
            ->select('owner')
            ->from(Usuario::class, 'owner')
            ->join(Archivo::class, 'a', 'WITH', 'a.owner = owner')
            ->join(Empleado::class, 'e', 'WITH', 'owner = e.usuario')
            ->join('e.convenio', 'c')
            ->where('c = :convenio')
            ->setParameter('convenio', $convenio)
            ->getQuery()
            ->getResult();
    }
}
