<?php


namespace App\Service\Configuracion\ServicioEmpleados;


use App\Entity\Halcon\Report\Nomina as HalconNomina;
use App\Entity\Halcon\Report\CertificadoLaboral as HalconCertificadoLaboral;
use App\Entity\Halcon\CertificadoIngresos as HalconCertificadoIngresos;
use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\Novasoft\Report\Nomina\Nomina as NovasoftNomina;
use App\Entity\Novasoft\Report\CertificadoLaboral as NovasoftCertificadoLaboral;
use App\Entity\Novasoft\Report\CertificadoIngresos as NovasoftCertificadoIngresos;
use App\Entity\Novasoft\Report\LiquidacionContrato as NovasoftLiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina as SeNomina;
use App\Entity\ServicioEmpleados\CertificadoLaboral as SeCertificadoLaboral;
use App\Entity\ServicioEmpleados\CertificadoIngresos as SeCertificadoIngresos;
use App\Entity\ServicioEmpleados\LiquidacionContrato as SeLiquidacionContrato;

class ServicioEmpleados
{
    private $reportEnitites = [
        'halcon' => [
            'Nomina' => HalconNomina::class,
            'CertificadoLaboral' => HalconCertificadoLaboral::class,
            'CertificadoIngresos' => HalconCertificadoIngresos::class,
            'LiquidacionContrato' => CabezaLiquidacion::class
        ],
        'novasoft' => [
            'Nomina' => NovasoftNomina::class,
            'CertificadoLaboral' => NovasoftCertificadoLaboral::class,
            'CertificadoIngresos' => NovasoftCertificadoIngresos::class,
            'LiquidacionContrato' => NovasoftLiquidacionContrato::class
        ],
        'se' => [
            'Nomina' => SeNomina::class,
            'CertificadoLaboral' => SeCertificadoLaboral::class,
            'CertificadoIngresos' => SeCertificadoIngresos::class,
            'LiquidacionContrato' => SeLiquidacionContrato::class
        ]
    ];

    private $reports = [];

    public function __construct($config)
    {
        foreach($config['report'] as $reportName => $reportConfig) {
            $this->reports[$reportName] = new Report($reportName, $config);
        }
    }

    public function getReportsNames()
    {
        return array_keys($this->reports);
    }

    public function getReportEntityClass($reportName, $source = 'se')
    {
        return $this->reportEnitites[$source][$reportName];
    }
}