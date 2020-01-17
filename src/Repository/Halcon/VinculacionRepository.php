<?php

namespace App\Repository\Halcon;

use App\Entity\Halcon\Compania;
use App\Entity\Halcon\Empresa;
use App\Entity\Halcon\PagoDetalle;
use App\Entity\Halcon\Periodo;
use App\Entity\Halcon\Vinculacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vinculacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vinculacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vinculacion[]    findAll()
 * @method Vinculacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinculacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vinculacion::class);
    }

    public function findComprobantesByIdent($ident = null)
    {
        $qb = $this->createQueryBuilder('v');
        $qb
            ->select('e.nombre AS empresa, v.noContrat AS contrato, c.nombre AS compania, v.nitTercer')
            ->addSelect("IFNULL(p.consecLiq, 'undefined') AS consecutivo")
            ->addSelect("IFNULL(DATE_FORMAT(p.liqDesde, '%Y-%m-%d'), 'undefined') AS fecha")
            ->leftJoin(PagoDetalle::class, 'pd', 'WITH', 'v.noContrat = pd.noContrat')
            ->leftJoin(Periodo::class, 'p', 'WITH', 'pd.consecLiq = p.consecLiq')
            ->leftJoin(Empresa::class, 'e', 'WITH', 'v.usuario = e.usuario')
            ->leftJoin(Compania::class, 'c', 'WITH', 'e.compania = c.compania')
            ->groupBy('v.usuario, v.noContrat, p.consecLiq')
            ->orderBy("fecha", "ASC")
        ;
        if($ident) {
            $qb->where('v.nitTercer = :ident')
                ->setParameter('ident', $ident);
        }

        return $qb->getQuery()->getResult();
    }



    public function findAllNitTerceros()
    {
        return $this->createQueryBuilder('v')
            ->select('v.nitTercer')
            ->where('v.nitTercer != 0')
            ->groupBy('v.nitTercer')
            ->getQuery()
            ->getResult('FETCH_COLUMN');
    }

    public function countAllDistinctComprobantes($identificacion = null)
    {
        $qb = $this->createQueryBuilder('v')
            ->select('v.noContrat')
            ->leftJoin(PagoDetalle::class, 'pd', 'WITH', 'v.noContrat = pd.noContrat')
            ->leftJoin(Periodo::class, 'p', 'WITH', 'pd.consecLiq = p.consecLiq')
            ->groupBy('v.noContrat, p.consecLiq');

        if($identificacion) {
            if(!is_array($identificacion) || count($identificacion) === 1) {
                $identificacion = is_array($identificacion) ? $identificacion[0] : $identificacion;
                $qb->where("v.nitTercer = '$identificacion'");
            } else {
                $qb->where($qb->expr()->in('v.nitTercer', $identificacion));
            }
        }

        $subQuery = $qb->getQuery()->getSQL();
        $query = "SELECT COUNT(*) FROM ($subQuery)x";

        $statement = $this->_em->getConnection()->prepare($query);
        $statement->execute();
        return (int)$statement->fetchColumn();
    }
}
