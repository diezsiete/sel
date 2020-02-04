<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\Tercero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Tercero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tercero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tercero[]    findAll()
 * @method Tercero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerceroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tercero::class);
    }

    /**
     * @return Tercero[]
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
        return (int)$this->allToImportQuery()->select('COUNT(t.nitTercer)')->getQuery()->getSingleScalarResult();
    }

    protected function allToImportQuery()
    {
        $qb = $this->createQueryBuilder('t');
        return $qb->where($qb->expr()->andX(
            $qb->expr()->neq('t.nitTercer', '0'),
            $qb->expr()->like('t.nombre', $qb->expr()->literal('%/%'))
        ));
    }
}
