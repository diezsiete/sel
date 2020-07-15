<?php


namespace App\Repository\Halcon\Report;


use App\Entity\Halcon\Report\Nomina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

class NominaRepository extends ServiceEntityRepository
{
    /**
     * @var NominaRenglonRepository
     */
    private $nominaRenglonRepo;

    public function __construct(ManagerRegistry $registry, NominaRenglonRepository $nominaRenglonRepo)
    {
        parent::__construct($registry, Nomina::class);
        $this->nominaRenglonRepo = $nominaRenglonRepo;
    }

    public function findNomina($noContrat, $consecLiq, $nitTercer)
    {
        $sqlCabecera =
            "SELECT 
            {$this->ifnull("e.usuario")} AS usuario,
            CONCAT(TRIM({$this->ifnull("co.nombre")}), ' - ', TRIM({$this->ifnull("e.nombre")})) AS CompaniaEmpresa, 
            {$this->ifnull("co.nit")} AS Nit,
            {$this->ifnull("cc.nombre")} AS CCostos,
            {$this->ifnull("p.consec_liq")} AS ConsecLiq,
            p.mensaje AS mensaje, 
            TRIM(REPLACE(REPLACE({$this->ifnull("t.nombre")}, '//', ' '), '/', ' ')) AS Nombre,
            {$this->ifnull("t.nit_tercer")} AS Documento,
            {$this->ifnull("car.nombre")} AS Cargo,
            CONCAT ( DATE_FORMAT(p.liq_desde, '%Y-%m-%d'), ' - ', DATE_FORMAT(p.liq_hasta, '%Y-%m-%d')) AS Periodo,
            FORMAT({$this->ifnull("v.sueldo_mes")}, 0) AS Basico,
            {$this->ifnull("b.nombre")} AS Banco,
            {$this->ifnull("t.cuenta")} AS Cuenta
            FROM pago_detalle pd
            LEFT JOIN vinculacion v ON pd.no_contrat = v.no_contrat
            LEFT JOIN periodo p ON pd.consec_liq = p.consec_liq
            LEFT JOIN empresa e ON v.usuario = e.usuario
            LEFT JOIN compania co ON e.compania = co.compania
            LEFT JOIN centro_costos cc ON v.centro_cos = cc.centro_cos
            LEFT JOIN cargo car ON v.cargo = car.cargo
            LEFT JOIN tercero t ON v.nit_tercer = t.nit_tercer
            LEFT JOIN banco b ON t.banco = b.banco
            WHERE v.no_contrat = ?
            AND p.consec_liq = ?
            AND v.nit_tercer = ?
            GROUP BY v.no_contrat, p.consec_liq";

        $query = $this->_em->createNativeQuery($sqlCabecera, $this->getResultSetMapping());
        $query
            ->setParameter(1, $noContrat)
            ->setParameter(2, $consecLiq)
            ->setParameter(3, $nitTercer);

        $result = $query->getResult();
        if($result) {
            foreach($result as $nomina) {
                $nomina->devengados = $this->nominaRenglonRepo->findDevengados($noContrat, $consecLiq, $nitTercer);
                $nomina->deducciones = $this->nominaRenglonRepo->findDeducciones($noContrat, $consecLiq, $nitTercer);
            }
        }
        return $result;
    }

    private function getResultSetMapping()
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult(Nomina::class, 'n')
            ->addFieldResult('n', 'usuario', 'usuario')
            ->addFieldResult('n', 'CompaniaEmpresa', 'companiaEmpresa')
            ->addFieldResult('n', 'Nit', 'nit')
            ->addFieldResult('n', 'CCostos', 'centroCostos')
            ->addFieldResult('n', 'ConsecLiq', 'consecutivoLiquidacion')
            ->addFieldResult('n', 'Nombre', 'nombre')
            ->addFieldResult('n', 'Documento', 'documento')
            ->addFieldResult('n', 'Cargo', 'cargo')
            ->addFieldResult('n', 'Periodo', 'periodo')
            ->addFieldResult('n', 'Basico', 'basico')
            ->addFieldResult('n', 'Banco', 'banco')
            ->addFieldResult('n', 'Cuenta', 'cuenta')
            ->addFieldResult('n', 'mensaje', 'mensaje')
        ;
        return $rsm;
    }

    private function ifnull($field, $expr2 = 'SIN DEFINIR'){
        return "IFNULL({$field}, '{$expr2}')";
    }
}