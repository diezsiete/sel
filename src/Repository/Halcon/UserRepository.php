<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[]
     */
    public function findAllToImport()
    {
        return $this->allToImportQuery()->getQuery()->getResult();
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return mixed
     */
    public function countAllToImport()
    {
        return (int)$this->allToImportQuery()->select('COUNT(u.id)')->getQuery()->getSingleScalarResult();
    }

    protected function allToImportQuery()
    {
        $qb = $this->createQueryBuilder('u');
        return $qb->where($qb->expr()->like('u.name', $qb->expr()->literal('%/%')));
    }
}
