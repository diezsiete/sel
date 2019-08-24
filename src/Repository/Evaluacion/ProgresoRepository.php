<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Progreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Progreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Progreso[]    findAll()
 * @method Progreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Progreso::class);
    }

    /**
     * @param Usuario $usuario
     * @param Evaluacion $evaluacion
     * @return Progreso
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByUsuarioElseNew(Usuario $usuario, Evaluacion $evaluacion)
    {
        $progreso = $this->createQueryBuilder('p')
            ->andWhere('p.usuario = :usuario')
            ->andWhere('p.evaluacion = :evaluacion')
            ->setParameter('usuario', $usuario)
            ->setParameter('evaluacion', $evaluacion)
            ->getQuery()
            ->getOneOrNullResult();
        if(!$progreso) {
            $progreso = $this->instanceNewProgreso($usuario, $evaluacion);
        }
        return $progreso;
    }

    /**
     * @param Usuario $usuario
     * @param Evaluacion $evaluacion
     * @return Progreso
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function instanceNewProgreso(Usuario $usuario, Evaluacion $evaluacion)
    {
        $modulo = $this->_em->getRepository(Modulo::class)->findFirst();
        $diapositiva = $modulo->getDiapositivas()->first();
        return (new Progreso())
            ->setUsuario($usuario)
            ->setEvaluacion($evaluacion)
            ->setModulo($modulo)
            ->setDiapositiva($diapositiva);
    }
}
