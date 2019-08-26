<?php

namespace App\Repository\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Respuesta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Respuesta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Respuesta[]    findAll()
 * @method Respuesta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RespuestaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Respuesta::class);
    }

    public static function getByPreguntaCriteria($pregunta)
    {
        if($pregunta instanceof Pregunta) {
            return Criteria::create()->where(
                Criteria::expr()->eq('pregunta', $pregunta)
            );
        } else {
            return Criteria::create()->where(
                Criteria::expr()->in('pregunta', $pregunta->toArray())
            );
        }
    }

    /**
     * @param Progreso $progreso
     * @param Pregunta|Pregunta[]|ArrayCollection $pregunta
     * @return Respuesta|Respuesta[]|null
     * @throws NonUniqueResultException
     */
    public function getRespuesta(Progreso $progreso, $pregunta)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.progreso = :progreso')
            ->join('r.pregunta', 'p')
            ->setParameter('progreso', $progreso)
            ->orderBy('p.indice', 'ASC')
            ->setParameter('pregunta', $pregunta);

        if($pregunta instanceof Pregunta) {
            return $qb->andWhere('r.pregunta = :pregunta')
                ->getQuery()
                ->getOneOrNullResult();
        } else {
            return $qb
                ->andWhere('r.pregunta IN (:pregunta)')
                ->getQuery()
                ->getResult();
        }
    }
}
