<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Presentacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Diapositiva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diapositiva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diapositiva[]    findAll()
 * @method Diapositiva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiapositivaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Diapositiva::class);
    }

    /**
     * @param int|int[] $indice
     * @param Presentacion $presentacion
     * @return Diapositiva|Diapositiva[]
     */
    public function findByIndice($indice, ?Presentacion $presentacion = null)
    {
        $qb = $this->createQueryBuilder('d')
            ->orderBy('d.indice', 'ASC');
        if($presentacion) {
            $qb->andWhere('d.presentacion = :presentacion')
                ->setParameter('presentacion', $presentacion);
        }
        if(is_array($indice)) {
            $qb->andWhere(
                $qb->expr()->in('d.indice', $indice)
            );
            return $qb->getQuery()->getResult();
        } else {
            $qb->andWhere('d.indice = :indice')
                ->setParameter('indice', $indice);

            try {
                return $qb->getQuery()->getOneOrNullResult();
            } catch (NonUniqueResultException $e) {
                return null;
            }
        }
    }


    /*
    public function findOneBySomeField($value): ?Diapositiva
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
