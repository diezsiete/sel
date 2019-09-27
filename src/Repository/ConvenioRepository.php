<?php

namespace App\Repository;

use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Representante;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Convenio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Convenio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Convenio[]    findAll()
 * @method Convenio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConvenioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Convenio::class);
    }

    /**
     * @return string[]
     */
    public function findAllCodigos()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.codigo');
        return $qb->getQuery()->getResult('FETCH_COLUMN');
    }

    /**
     * Consulta para la vista de listado de convenios. Muestra los convenios con num. empleados y usuarios asignados
     * @param Usuario|null $usuario
     * @param string $search
     * @param bool $conEmpleados
     * @return array[
     *  "codigo" => string,
     *  "nombre" => string,
     *  "codigoCliene" => string,
     *  "direccion" => string,
     *  "empleados" => int,
     *  "usuarioCliente" => Usuario[],
     *  "usuarioEmpresa" => Usuario[]
     * ]
     */
    public function findConveniosWithUsuariosByRol(Usuario $usuario = null, $search = "", $conEmpleados = true)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->addSelect($qb->expr()->count('e') . ' AS empleados, e, r, u')
            ->leftJoin('c.empleados', 'e')
            ->leftJoin('c.representantes', 'r')
            ->leftJoin('r.usuario', 'u')
            // ->leftJoin(Empleado::class, 'e', 'WITH', 'e.convenio = c')
            // ->leftJoin(Representante::class, 'r', 'WITH', 'r.convenio = c')
            // ->leftJoin(Usuario::class, 'u', 'WITH', 'r.usuario = u')
            ->groupBy('c.codigo');
        if($conEmpleados) {
            $qb->having('empleados > 0');
        }

        if($usuario) {
            $qb->andWhere($qb->expr()->eq('r.usuario', $usuario->getId()));
        }

        if($search) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('c.codigo', ':search'),
                $qb->expr()->like('c.nombre', ':search'),
                $qb->expr()->like('u.primerNombre', ':search')
            ))->setParameter('search', "%$search%");
        }

        $paginator = new Paginator($qb->getQuery());
        $result = [];
        $i = 0;
        foreach($paginator as $row) {
            /** @var Convenio $convenio */
            $convenio = $row[0];
            $result[$i] = $convenio->jsonSerialize();
            $result[$i]['empleados'] = $row['empleados'];
            $usuarioCliente = [];
            $usuarioEmpresa = [];
            foreach($convenio->getRepresentantes() as $representante) {
                $usuario = $representante->getUsuario();
                if($usuario->esRol('/EMPRESA/')) {
                    $usuarioEmpresa[] = $usuario;
                } else {
                    $usuarioCliente[] = $usuario;
                }
            }
            $result[$i]["usuarioCliente"] = $usuarioCliente;
            $result[$i]["usuarioEmpresa"] = $usuarioEmpresa;
            $i++;
        }
        return $result;
    }


    /**
     * @param $ident
     * @return Convenio|null
     */
    public function findConvenioByIdent($ident)
    {
        try {
            return $this
                ->createQueryBuilder('c')
                ->join('c.empleados', 'e')
                ->join('e.usuario', 'u')
                ->where('u.identificacion = :ident')
                ->setParameter('ident', $ident)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

}
