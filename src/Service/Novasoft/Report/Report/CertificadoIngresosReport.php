<?php
/**
 * Reporte datos certificado laboral
 */

namespace App\Service\Novasoft\Report\Report;


use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use App\Service\Novasoft\Report\Importer\CertificadoIngresosImporter;
use App\Service\Novasoft\Report\Mapper\CertificadoIngresosMapper;
use App\Service\Novasoft\Report\ReportFormatter;
use App\Service\ServicioEmpleados\Report\PdfHandler;
use App\Service\Utils;
use DateTime;
use SSRS\SSRSReport;
use SSRS\SSRSReportException;


class CertificadoIngresosReport extends Report
{
    protected $path = "/ReportesWeb/NOM/NOM921_17";

    /**
     * @var string
     */
    protected $parameter_Fec_ini = '1/1/2017';
    /**
     * @var string
     */
    protected $parameter_Fec_fin = '12/31/2017';
    /**
     * @var string
     */
    protected $parameter_Cod_Conv = "%";
    /**
     * @var string
     */
    protected $parameter_cod_cia = "%";
    /**
     * @var string
     */
    protected $parameter_cod_suc = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cco = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla1 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla2 = "%";
    /**
     * @var string
     */
    protected $parameter_Cod_Cla3 = "%";
    /**
     * @var
     */
    protected $parameter_fec_exp;
    /**
     * @var string
     */
    protected $parameter_Ccod_emp = "%";
    /**
     * @var string
     */
    private $ano;
    /**
     * @var array
     */
    private $anos = [];

    public function __construct(SSRSReport $SSRSReport, ReportFormatter $reportFormatter, Configuracion $configuracion,
                                Utils $utils, CertificadoIngresosMapper $mapper, CertificadoIngresosImporter $importer, PdfHandler $pdfHandler)
    {
        parent::__construct($SSRSReport, $reportFormatter, $configuracion, $utils, $mapper, $importer, $pdfHandler);
        $now = new DateTime();
        $this->parameter_fec_exp = $now->format('m/d/Y');
    }

    /**
     * @param string|string[] $ano
     * @return CertificadoIngresosReport
     */
    public function setParameterAno($ano)
    {
        if(is_array($ano)) {
            $this->anos = $ano;
            $ano = $this->anos[0];
        }
        $this->ano = $ano;
        $pattern = '/(.+\/)\d+$/i';
        $replacement = '${1}' . $ano;
        $this->parameter_Fec_ini = preg_replace($pattern, $replacement, $this->parameter_Fec_ini);
        $this->parameter_Fec_fin = preg_replace($pattern, $replacement, $this->parameter_Fec_fin);
        return $this;
    }

    /**
     * @param $identificacion
     * @return $this
     */
    public function setParameterCodigoEmpleado($identificacion)
    {
        $this->parameter_Ccod_emp = $identificacion;
        return $this;
    }

    /**
     * @return CertificadoIngresos|CertificadoIngresos[]|null
     * @throws SSRSReportException
     */
    public function renderMap()
    {
        if(!$this->anos) {
            $csvAssociative = $this->reportFormatter->csvContSplittedToAssociative($this->renderCSV());
            $map = $this->reportFormatter->setSsrsDb($this->db)->mapCsv($csvAssociative, $this->mapper);
            return $map ? $map[0] : null;
        } else {
            $anos = $this->anos;
            $this->anos = [];
            $map = [];
            while($anos) {
                if($certificado = $this->setParameterAno(array_shift($anos))->renderMap()) {
                    $map[] = $certificado;
                }
            }
            return $map;
        }
    }

    public function renderAssociative()
    {
        return $this->reportFormatter->csvContSplittedToAssociative($this->renderCSV());
    }

    public function getPdfFileName(): string
    {
        return 'novasoft/certificado-ingresos/' . $this->parameter_Ccod_emp . '-'.$this->ano.'.pdf';
    }

    public function setUsuario(Usuario $usuario)
    {
        parent::setUsuario($usuario);
        $this->parameter_Ccod_emp = $usuario->getIdentificacion();
        return $this;
    }

    /**
     * @param CertificadoIngresos $certificadoIngresos
     * @return CertificadoIngresosReport
     */
    public function setParametersByEntity($certificadoIngresos)
    {
        $this
            ->setParameterCodigoEmpleado($certificadoIngresos->getUsuario()->getIdentificacion())
            ->setParameterAno($certificadoIngresos->getAno())
            ->setDb($certificadoIngresos->getSsrsDb() ? $certificadoIngresos->getSsrsDb() : $this->db);
        return $this;
    }
}