<?php


namespace App\Service\Pdf\ServicioEmpleados\Report;

use App\Service\Configuracion\Configuracion;
use FPDI;

class CertificadoIngresosPdf extends FPDI
{
    private $fontSize = 10;
    private $customFontFamily = 'Arial';

    /**
     * @var Configuracion
     */
    private $configuracion;

    /**
     * @required
     * @param Configuracion $configuracion
     */
    public function setConfiguracion(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->fpdftxt($txt), $border, $ln, $align, $fill, $link);
    }

    private function fpdftxt($txt){
        if(function_exists('iconv'))
            return  iconv('UTF-8', 'windows-1252', $txt);
        return $txt;
    }

    public function getRMargin(){
        return $this->GetPageWidth() - $this->rMargin;
    }

    public function build(CertificadoIngresosInterface $certificadoIngresos)
    {

        // add a page
        $this->AddPage();
        $this->setSourceFile($this->getSourceFile($certificadoIngresos->getFechaInicial()->format('Y')));

        // import page 1
        $tplIdx = $this->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $this->useTemplate($tplIdx);

        $this->SetFont($this->customFontFamily,'', $this->fontSize);

        //Numero documento
        $x = 147;
        $y = 27;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getNumeroFormulario());

        //NIT
        $x = 66.5;
        $y = 36;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getDv());
        $x -= 4.3;
        $this->SetX($x);
        for($i = strlen($certificadoIngresos->getNit())-1; $i >=0; $i--){
            $this->Write(0, $certificadoIngresos->getNit()[$i]);
            $x -= 3.79;
            $this->SetX($x);
        }

        //razon social
        $x = 16;
        $y = 43.5;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getRazonSocial());

        //Tipo de documento
        $y = 53;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getTipoDocumento());

        //Numero de identificacion
        $x = 28;
        $this->setX($x);
        for($i = 0, $iMax = strlen($certificadoIngresos->getIdentificacion()); $i < $iMax; $i++){
            $this->Write(0, $certificadoIngresos->getIdentificacion()[$i]);
            $x += 3.79;
            $this->SetX($x);
        }

        //Apellidos y nombres
        $x = 83;
        $this->setX($x);
        $this->Write(0, $certificadoIngresos->getPrimerApellido());
        $x = 115;
        $this->setX($x);
        $this->Write(0, $certificadoIngresos->getSegundoApellido());
        $x = 147;
        $this->setX($x);
        $this->Write(0, $certificadoIngresos->getPrimerNombre());
        if($certificadoIngresos->getSegundoNombre()){
            $x = 179;
            $this->setX($x);
            $this->Write(0, $certificadoIngresos->getSegundoNombre());
        }

        //Periodo de la certificacion
        $x = 19;
        $y = 61.5;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getFechaInicial()->format('Y'));
        $this->SetX($x + 12);
        $this->Write(0, $certificadoIngresos->getFechaInicial()->format('m'));
        $this->SetX($x + 20);
        $this->Write(0, $certificadoIngresos->getFechaInicial()->format('d'));
        $this->SetXY($x + 40, $y);
        $this->Write(0, $certificadoIngresos->getFechaFinal()->format('Y'));
        $this->SetX($x + 51);
        $this->Write(0, $certificadoIngresos->getFechaFinal()->format('m'));
        $this->SetX($x + 59);
        $this->Write(0, $certificadoIngresos->getFechaFinal()->format('d'));

        //Fecha de expedición
        $x= 90;
        $this->SetX($x);
        $this->Write(0, $certificadoIngresos->getFechaExpedicion()->format('Y'));
        $this->SetX($x + 12);
        $this->Write(0, $certificadoIngresos->getFechaExpedicion()->format('m'));
        $this->SetX($x + 20);
        $this->Write(0, $certificadoIngresos->getFechaExpedicion()->format('d'));

        //Lugar
        $x= 122;
        $this->SetX($x);
        $this->Write(0, $certificadoIngresos->getCiudad());

        //Cod Depto
        $x= 184;
        $this->SetX($x);
        $this->Write(0, $certificadoIngresos->getDepartamentoCodigo());

        //Cod Ciudad
        $x= 194;
        $this->SetX($x);
        $this->Write(0, $certificadoIngresos->getCiudadCodigo());

        $this->fontSize -= 2;
        $this->SetFont($this->customFontFamily,'',$this->fontSize);

        //Numero de agencias
        $this->SetXY(148, 66);
        $this->Write(0, 1);

        //Conceptos
        $y = 74;
        $y = $this->conceptoIngresos($certificadoIngresos, $y);
        $y += 4.5;
        $y = $this->conceptoAportes($certificadoIngresos, $y);

        $this->fontSize += 2;

        //Nombre del pagador o agente retenedor
        $this->SetFont($this->customFontFamily,'',$this->fontSize);
        $x = 10;
        $y += 4;
        $this->SetXY($x, $y);
        $this->Write(0, 'FORMA CONTINUA IMPRESA POR COMPUTADOR NO NECESITA FIRMAR AUTOGRAFO (ART 10 D.R. 836/91)');

        $y += 4.25 * 10;
        $this->fontSize -= 2;
        $this->SetFont($this->customFontFamily,'',$this->fontSize);
        $this->writeRightCell((string)number_format($certificadoIngresos->getMonto('valorRetencion')), $y);

        return $this;
    }

    private function getSourceFile($ano)
    {
        $pathTemplate = 'build/' . $this->configuracion->getEmpresa(true) . '/images/sel';
        $file = $this->configuracion->getPdfResourcePath($pathTemplate . '/220/220-'.$ano.'.pdf');
        return file_exists($file)
            ? $file
            : $this->configuracion->getPdfResourcePath( $pathTemplate . '/220/220-2015.pdf');
    }

    private function conceptoIngresos(CertificadoIngresosInterface $certificadoIngresos, $y)
    {
        $montosProperties = $certificadoIngresos->getIngresoProperties();
        $montos = [];
        foreach($montosProperties as $montoProperty) {
            $montos[] = (string)number_format($certificadoIngresos->getMonto($montoProperty));
        }
        $montos[] = (string)number_format($certificadoIngresos->getIngresoTotal());
        return $this->setDetalleRows($montos, $y);
    }

    private function conceptoAportes(CertificadoIngresosInterface $certificadoIngresos, $y)
    {
        $montosProperties = $certificadoIngresos->getAportesProperties();
        $montos = [];
        foreach($montosProperties as $montoProperty) {
            $montos[] = (string)number_format($certificadoIngresos->getMonto($montoProperty));
        }
        return $this->setDetalleRows($montos, $y);
    }

    private function setDetalleRows($values, float $y)
    {
        for($i = 0, $iMax = count($values); $i < $iMax; $i++) {
            if($i+1 === $iMax) {
                $this->SetFont($this->customFontFamily,'B',$this->fontSize);
            }
            $this->writeRightCell($values[$i], $y);
            $y += 4.25;
        }
        $this->SetFont($this->customFontFamily,'',$this->fontSize);
        return $y;
    }

    private function writeRightCell($value, $y)
    {
        $x = $this->getRMargin() - $this->GetStringWidth($value) - 3;
        $this->SetXY($x, $y);
        $this->Write(0, $value);
    }

}