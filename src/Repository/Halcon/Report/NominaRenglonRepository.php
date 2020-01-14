<?php


namespace App\Repository\Halcon\Report;

use App\Entity\Halcon\Report\NominaRenglon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NominaRenglonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NominaRenglon::class);
    }
    
    public function findDevengados($noContrat, $consecLiq, $nitTercer)
    {
        return $this->findRenglonResult($this->sqlDevengado(), $noContrat, $consecLiq, $nitTercer);
    }

    public function findDeducciones($noContrat, $consecLiq, $nitTercer)
    {
        return $this->findRenglonResult($this->sqlDeduccion(), $noContrat, $consecLiq, $nitTercer);
    }

    private function sqlDevengado()
    {
        return $this->sqlRenglon();
    }

    private function sqlDeduccion()
    {
        $sql = $this->sqlRenglon();
        return str_replace("$'", "$' = 0", $sql);
    }

    private function sqlRenglon()
    {
        return "
            SELECT * FROM (
            SELECT 
            pd.no_contrat AS Contrato,
            pd.consec_liq AS Consecutivo,
            CONCAT({$this->ifnull("con.concepto")}, ' ', {$this->ifnull("con.nombre")}) AS Concepto,
            pd.novedad AS Novedad,
            {$this->ifnull("con.novedad_en")} AS NovedadEn,
            FORMAT(SUM(pd.valor), 0) AS Total
            FROM pago_detalle pd
            LEFT JOIN concepto con ON pd.concepto = con.concepto
            LEFT JOIN vinculacion v ON pd.no_contrat = v.no_contrat
            LEFT JOIN empresa e ON v.usuario = e.usuario
            LEFT JOIN compania co ON e.compania = co.compania
            WHERE pd.no_contrat = ? 
            AND pd.consec_liq = ?
            AND v.nit_tercer = ? 
            AND con.concepto REGEXP '^(-|[a-z]|[A-Z]|[0-9])(-|[a-z]|[A-Z]|[0-9])([0-4]|50).*$'
            GROUP BY pd.no_contrat, pd.consec_liq, pd.concepto 
            WITH ROLLUP
            ) A
            WHERE !ISNULL(Contrato) AND !ISNULL(Consecutivo)";

    }

    private function getResultSetMapping()
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult(NominaRenglon::class, 'nr')
            ->addFieldResult('nr', 'Contrato', 'contrato')
            ->addFieldResult('nr', 'Consecutivo', 'consecutivo')
            ->addFieldResult('nr', 'Concepto', 'concepto')
            ->addFieldResult('nr', 'Novedad', 'novedad')
            ->addFieldResult('nr', 'NovedadEn', 'novedadEn')
            ->addFieldResult('nr', 'Total', 'total')
        ;
        return $rsm;
    }

    private function findRenglonResult($sql, $noContrat, $consecLiq, $nitTercer)
    {
        $query = $this->_em->createNativeQuery($sql, $this->getResultSetMapping());
        $query
            ->setParameter(1, $noContrat)
            ->setParameter(2, $consecLiq)
            ->setParameter(3, $nitTercer);

        return $query->getResult();
    }

    private function ifnull($field, $expr2 = 'SIN DEFINIR'){
        return "IFNULL({$field}, '{$expr2}')";
    }
}