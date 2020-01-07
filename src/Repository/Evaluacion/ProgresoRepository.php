<?php

namespace App\Repository\Evaluacion;

use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Main\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param UserInterface $usuario
     * @param Evaluacion $evaluacion
     * @return Progreso
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findByUsuarioElseNew(UserInterface $usuario, Evaluacion $evaluacion)
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

    public function findByEvaluacionSlug($id, $evaluacionSlug)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('e')
            ->join('p.evaluacion', 'e')
            ->where('e.slug = :slug')
            ->andWhere('p.id = :id')
            ->setParameter('slug', $evaluacionSlug)
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param UserInterface $usuario
     * @param Evaluacion $evaluacion
     * @return Progreso
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function instanceNewProgreso(UserInterface $usuario, Evaluacion $evaluacion)
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
