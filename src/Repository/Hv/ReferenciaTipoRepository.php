<?php

namespace App\Repository\Hv;

use App\Entity\Hv\ReferenciaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReferenciaTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferenciaTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferenciaTipo[]    findAll()
 * @method ReferenciaTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferenciaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReferenciaTipo::class);
    }

    public function getKeyPair()
    {
        return $this->createQueryBuilder('rt')
            ->select('rt.id, rt.nombre')
            ->getQuery()
            ->getResult('FETCH_KEY_PAIR');
    }
}
