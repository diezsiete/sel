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

    public function findComprobantesByIdent($ident)
    {
        $sql = "
            SELECT e.nombre AS Empresa, v.no_contrat AS Contrato, 
              IFNULL(p.consec_liq, 'Sin definir') AS Consecutivo, 
              IFNULL(DATE_FORMAT(p.liq_desde, '%Y-%m-%d'), 'Sin definir') AS Fecha 
            FROM vinculacion v 
            LEFT JOIN pago_detalle pd ON v.no_contrat = pd.no_contrat 
            LEFT JOIN periodo p ON pd.consec_liq = p.consec_liq 
            LEFT JOIN empresa e ON v.usuario = e.usuario 
            LEFT JOIN compania c ON e.compania = c.compania 
            WHERE v.nit_tercer = '".$ident ."' 
            GROUP BY v.usuario, v.no_contrat, p.consec_liq 
            ORDER BY Fecha DESC";

        $qb = $this->createQueryBuilder('v');
        $qb
            ->select('e.nombre AS Empresa, v.noContrat AS Contrato, c.nombre AS Compania')
            ->addSelect("IFNULL(p.consecLiq, 'Sin definir') AS Consecutivo")
            ->addSelect("IFNULL(DATE_FORMAT(p.liqDesde, '%Y-%m-%d'), 'Sin definir') AS Fecha")
            ->leftJoin(PagoDetalle::class, 'pd', 'WITH', 'v.noContrat = pd.noContrat')
            ->leftJoin(Periodo::class, 'p', 'WITH', 'pd.consecLiq = p.consecLiq')
            ->leftJoin(Empresa::class, 'e', 'WITH', 'v.usuario = e.usuario')
            ->leftJoin(Compania::class, 'c', 'WITH', 'e.compania = c.compania')
            ->where('v.nitTercer = :ident')
            ->groupBy('v.usuario, v.noContrat, p.consecLiq')
            ->orderBy("Fecha", "DESC")
            ->setParameter('ident', $ident);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Vinculacion[] Returns an array of Vinculacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vinculacion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
