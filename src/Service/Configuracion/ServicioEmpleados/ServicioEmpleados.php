<?php


namespace App\Service\Configuracion\ServicioEmpleados;


use App\Entity\Halcon\Report\Nomina as HalconNomina;
use App\Entity\Halcon\Report\CertificadoLaboral as HalconCertificadoLaboral;
use App\Entity\Halcon\CertificadoIngresos as HalconCertificadoIngresos;
use App\Entity\Halcon\CabezaLiquidacion;
use App\Entity\Main\Usuario;
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
    private $configReport;
    private $reports = [];

    //private $sourcesRoles = ['halcon' => 'ROLE_HALCON', 'novasoft' => 'ROLE_EMPLEADO', 'napi' => 'ROLE_EMPLEADO'];
    private $sourcesRoles = ['halcon' => 'ROLE_HALCON', 'napi' => 'ROLE_EMPLEADO'];

    public function __construct($config)
    {
        $this->configReport = $config['report'];
    }

    public function getReportsNames()
    {
        return array_keys($this->configReport);
    }

    public function getReportEntityClass($reportName, $source = 'se')
    {
        return $this->reportEnitites[$source][$reportName];
    }

    public function usuarioHasRoleForSource(Usuario $usuario, $source)
    {
        return $usuario->esRol($this->sourcesRoles[$source]);
    }

    public function getRolBySource($source)
    {
        return $this->sourcesRoles[$source];
    }

    /**
     * @return array
     */
    public function getSources()
    {
        return array_keys($this->sourcesRoles);
    }

    /**
     * @param $reportName
     * @return Report|CertificadoIngresos
     */
    public function getReportConfig($reportName)
    {
        return $this->instantiateReportConfig($reportName);
    }

    /**
     * @return CertificadoIngresos
     */
    public function getCertificadoIngresosConfig()
    {
        return $this->instantiateReportConfig('CertificadoIngresos');
    }

    private function instantiateReportConfig($reportName)
    {
        if(!isset($this->reports[$reportName])) {
            if($reportName === 'CertificadoIngresos') {
                $this->reports[$reportName] = new CertificadoIngresos($reportName, $this->configReport[$reportName]);
            } else {
                $this->reports[$reportName] = new Report($reportName, $this->configReport[$reportName]);
            }
        }
        return $this->reports[$reportName];
    }
}