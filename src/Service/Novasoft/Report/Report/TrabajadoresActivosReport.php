<?php


namespace App\Service\Novasoft\Report\Report;


use App\Entity\Convenio;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Mapper\MapperTrabajadoresActivos;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\Utils;
use DateTimeInterface;
use SSRS\SSRSReport;

/**
 * Class TrabajadoresActivosReport
 * @package App\Service\Novasoft\Report\Report
 * @method TrabajadorActivo[] renderMap()
 */
class TrabajadoresActivosReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM1527";

    /**
     * Fecha Consulta Reporte
     * Si no se asigna toma hoy
     * @var DateTimeInterface
     */
    protected $parameter_Fecha;

    /**
     * Convenio
     * @var string
     */
    protected $parameter_Cod_Conv = "%";

    /**
     * Compañia
     */
    protected $parameter_Cod_Cia = "%";

    /**
     * Sucursal
     */
    protected $parameter_Cod_Suc = "%";

    /**
     * C.Costo
     */
    protected $parameter_Cod_Cco = "%";

    /**
     * Clasif. 1:
     */
    protected $parameter_Cod_Cl1 = "%";

    /**
     * Clasif.2
     */
    protected $parameter_Cod_Cl2 = "%";

    /**
     * Clasif.3
     */
    protected $parameter_Cod_Cl3 = "%";


    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, MapperTrabajadoresActivos $mapper)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper);
    }

    protected function normalizeParameter_Fecha()
    {
        if(!$this->parameter_Fecha) {
            return $this->utils->dateFormatToday('m/d/Y');
        } else {
            return $this->parameter_Fecha->format('m/d/Y');
        }
    }

    public function setFecha(DateTimeInterface $fecha)
    {
        $this->parameter_Fecha = $fecha;
    }

    /**
     * @param Convenio|string $convenio
     */
    public function setConvenio($convenio)
    {
        $this->parameter_Cod_Conv = is_object($convenio) ? $convenio->getCodigo() : $convenio;
    }
}