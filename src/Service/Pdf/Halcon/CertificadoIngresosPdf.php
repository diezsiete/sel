<?php


namespace App\Service\Pdf\Halcon;


use App\Entity\Halcon\CertificadoIngresos;
use App\Service\Configuracion\Configuracion;
use FPDI;

class CertificadoIngresosPdf extends FPDI
{
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

    public function build(CertificadoIngresos $certificadoIngresos)
    {

        $nit_explode = explode('-', $certificadoIngresos->getCompaniaNit());
        $tercero_nombre = explode("/", $certificadoIngresos->getTercero()->getNombre(false));
        $tercero_nombre_pila = explode(" ", $tercero_nombre[2]);
        $tercero_nombre[2] = $tercero_nombre_pila[0];
        if(count($tercero_nombre_pila) > 1)
            $tercero_nombre[3] = $tercero_nombre_pila[1];
        $desde = explode("-", $certificadoIngresos->getDesde()->format('Y-m-d'));
        $hasta = explode("-", $certificadoIngresos->getHasta()->format('Y-m-d'));
        $expedicion = explode("-", date("Y-m-d"));
        $lugar = "BOGOTÁ";
        $cod_dpto = '1  1';
        $cod_ciud = '0  0  1';

        $total_ingresos_brutos = $certificadoIngresos->getTotalIngresosBrutos();


        $nombre_pagador = "FORMA CONTINUA IMPRESA POR COMPUTADOR NO NECESITA FIRMAR AUTOGRAFO (ART 10 D.R. 836/91)";

        $line_height = 5;
        $font_size   = 10;
        $image_width = 50;
        $border = 0;

        // add a page
        $this->AddPage();


        $pathTemplate = $this->configuracion->getWebDir(). '/build/' . $this->configuracion->getEmpresa(true)
            . '/images/sel';
        $file = $pathTemplate . '/220/220-'.$desde[0].'.pdf';


        $file = file_exists($file) ? $file : $pathTemplate . '/220/220-2015.pdf';
        $this->setSourceFile($file);

        // import page 1
        $tplIdx = $this->importPage(1);
        // use the imported page and place it at point 10,10 with a width of 100 mm
        $this->useTemplate($tplIdx);

        $this->SetFont('Arial','',$font_size);

        //NIT
        $x = 66.5;
        $y = 36;
        $this->SetXY($x, $y);
        $this->Write(0, $nit_explode[1]);
        $x -= 4.3;
        $this->SetX($x);
        for($i = strlen($nit_explode[0])-1; $i >=0; $i--){
            $this->Write(0, $nit_explode[0][$i]);
            $x -= 3.79;
            $this->SetX($x);
        }

        //razon social
        $x = 16;
        $y = 43.5;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getCompaniaNombre());

        //Tipo de documento
        $y = 53;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->terceroTipoNit());

        //Numero de identificacion
        $x = 28;
        $this->setX($x);
        for($i = 0; $i < strlen($certificadoIngresos->getTercero()->getNitTercer()); $i++){
            $this->Write(0, $certificadoIngresos->getTercero()->getNitTercer()[$i]);
            $x += 3.79;
            $this->SetX($x);
        }

        //Apellidos y nombres
        $x = 83;
        $this->setX($x);
        $this->Write(0, $tercero_nombre[0]);
        $x = 115;
        $this->setX($x);
        $this->Write(0, $tercero_nombre[1]);
        $x = 147;
        $this->setX($x);
        $this->Write(0, $tercero_nombre[2]);
        if(isset($tercero_nombre[3])){
            $x = 179;
            $this->setX($x);
            $this->Write(0, $tercero_nombre[3]);
        }

        //Periodo de la certificacion
        $x = 19;
        $y = 61.5;
        $this->SetXY($x, $y);
        $this->Write(0, $desde[0]);
        $this->SetX($x + 12);
        $this->Write(0, $desde[1]);
        $this->SetX($x + 20);
        $this->Write(0, $desde[2]);
        $this->SetXY($x + 40, $y);
        $this->Write(0, $hasta[0]);
        $this->SetX($x + 51);
        $this->Write(0, $hasta[1]);
        $this->SetX($x + 59);
        $this->Write(0, $hasta[2]);

        //Fecha de expedición
        $x= 90;
        $this->SetX($x);
        $this->Write(0, $expedicion[0]);
        $this->SetX($x + 12);
        $this->Write(0, $expedicion[1]);
        $this->SetX($x + 20);
        $this->Write(0, $expedicion[2]);

        //Lugar
        $x= 122;
        $this->SetX($x);
        $this->Write(0, $lugar);

        //Cod Depto
        $x= 184;
        $this->SetX($x);
        $this->Write(0, $cod_dpto);

        //Cod Ciudad
        $x= 194;
        $this->SetX($x);
        $this->Write(0, $cod_ciud);

        //Conceptos ingresos
        //pagos
        $this->SetFont('Arial','',$font_size-2);
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getSalarios()) - 3;
        $y = 74;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getSalarios());

        //cesantias
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getCesantias()) - 3;
        $y = 78.4;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getCesantias());

        //representacion
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getRepresenta()) - 3;
        $y = 82.7;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getRepresenta());

        //pension
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getPension()) - 3;
        $y = 87;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getPension());

        //otros ingresos
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getOtrosIng()) - 3;
        $y = 91;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getOtrosIng());

        //total ingresos brutos
        $x = $this->getRMargin() - $this->GetStringWidth($total_ingresos_brutos) - 3;
        $y = 95;
        $this->SetXY($x, $y);
        $this->Write(0, $total_ingresos_brutos);


        //Conceptos de los aportes
        //salud
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getEps()) - 3;
        $y = 104;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getEps());

        //afp
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getAfp()) - 3;
        $y = 108;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getAfp());

        //afc
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getAfpVol()) - 3;
        $y = 112;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getAfpVol());

        //rete fuente
        $x = $this->getRMargin() - $this->GetStringWidth($certificadoIngresos->getRetefuente()) - 3;
        $y = 116.5;
        $this->SetXY($x, $y);
        $this->Write(0, $certificadoIngresos->getRetefuente());


        //Nombre del pagador o agente retenedor
        $this->SetFont('Arial','',$font_size);
        $x = 10;
        $y = 126;
        $this->SetXY($x, $y);
        $this->Write(0, $nombre_pagador);


        return $this;
    }

}