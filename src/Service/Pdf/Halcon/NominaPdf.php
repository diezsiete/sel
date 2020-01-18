<?php


namespace App\Service\Pdf\Halcon;

use App\Entity\Halcon\Report\Nomina;
use App\Entity\Halcon\Report\NominaRenglon;
use App\Service\Pdf\PdfBase;

class NominaPdf extends HalconPdfBase
{

    protected $width = 110;
    protected $widthNovedad = 50;
    protected $fntSize = 10;

    public function Header()
    {

    }

    public function build(Nomina $nomina)
    {
        $logoImg = $this->compania->getLogoPdf();

        $devengadosTotal  = $nomina->devengados  ? str_replace(',', '', end($nomina->devengados)->total) : 0;
        $deduccionesTotal = $nomina->deducciones ? str_replace(',', '', end($nomina->deducciones)->total) : 0;

        $neto =  number_format($devengadosTotal + $deduccionesTotal, 0);

        /**
         * IMPRESION PDF
         */
        $line_height = $this->lineHeight = 5;
        $font_size   = $this->fntSize;
        $image_width = 38;
        $border = 0;
        $logo_margin = 0;
        $width = $width_conceptos = $this->width;
        $width_novedad = $this->widthNovedad;

        $this->AddPage();
        $this->SetFont('Arial', '', $font_size);
        $this->Image($logoImg ,$this->GetX(), $this->getY() - 3, $image_width);

        $this->SetFont('Arial','B',$font_size + 10);
        $this->Cell(200, $line_height + $logo_margin, 'COMPROBANTE DE PAGO', $border, 0, 'R');
        $this->SetFont('Arial','B',$font_size);
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();

        //Datos Empresa
        $this->Cell($this->GetStringWidth($nomina->companiaEmpresa), $line_height, $nomina->companiaEmpresa, $border, 0);
        $txt = 'Consecutivo Liq : ' . $nomina->consecutivoLiquidacion;
        $this->SetX($this->getRMargin() - $this->GetStringWidth($txt));
        $this->Cell($this->GetStringWidth($txt), $line_height, $txt, $border, 1);
        $this->Cell(0, $line_height, 'Nit : ' . $nomina->nit, $border, 1);
        $this->Cell(0, $line_height, 'C.Costos : ' . $nomina->centroCostos, $border, 1);

        $this->Ln();

        //Datos tercero
        $this->SetFont('Arial','',$font_size);
        $this->Cell($width, $line_height, 'Nombre : ' . $nomina->nombre, $border);
        $this->Cell(0, $line_height, 'Documento : ' . $nomina->documento,$border,1);
        $this->Cell($width, $line_height, 'Cargo : ' . $nomina->cargo, $border);
        $this->Cell(0, $line_height, 'Periodo : ' . $nomina->periodo,$border,1);
        $this->Cell($width, $line_height, 'Banco : ' . $nomina->banco, $border);
        $this->Cell(0, $line_height, 'Cuenta : ' . $nomina->cuenta, $border,1);
        //TODO : formatear con puntos
        $this->Cell($width, $line_height, 'Básico : ' . $nomina->basico, $border);

        //Devengados y Deducciones
        $line_height = $this->lineHeight = 6;

        ///Devengados
        $this->Ln();
        $this->Ln();
        $this->printDevengadosDeducciones('DEVENGADOS', $nomina->devengados);

        //Deducciones
        $this->Ln();
        $this->Ln();
        $this->printDevengadosDeducciones('DEDUCCIONES', $nomina->deducciones);

        $this->Ln();
        $this->Ln();

        //Neto
        $this->setX($this->getX() + $width_conceptos);
        $this->Cell($width_novedad, $line_height, 'NETO ', 1);
        $this->Cell(0, $line_height, $neto, 1, 1, 'R');

        //Msj
        if(!empty(trim($nomina->mensaje))){
            $this->Ln();
            $this->Cell(0, $line_height, 'Mensaje : ', 0, 1);
            $this->SetFont('Arial','',$font_size);
            $this->MultiCell(0, $line_height -2, $nomina->mensaje);
        }

        return $this;
    }

    /**
     * @param $titulo
     * @param NominaRenglon[] $rs
     */
    private function printDevengadosDeducciones($titulo, $rs) {


        $this->SetFont('Arial','B', $this->fntSize);
        $this->Cell(0, $this->lineHeight, $titulo, 1, 1, 'C');
        $this->Cell($this->width, $this->lineHeight, 'CONCEPTOS', 1, 0, 'L');
        $this->Cell($this->widthNovedad, $this->lineHeight, 'NOVEDAD', 1, 0, 'L');
        $this->Cell(0, $this->lineHeight, 'TOTAL', 1, 1, 'L');
        //Datos
        $this->SetFont('Arial','',$this->fntSize);
        $i = count($rs);
        foreach($rs as $pago_detalle){
            if($i > 1){
                $border_bottom = $i - 1 == 1 ? 'B' : '';
                $this->Cell($this->width, $this->lineHeight, $pago_detalle->concepto, 'L'.$border_bottom);
                $this->Cell($this->widthNovedad / 2, $this->lineHeight, $pago_detalle->novedad, $border_bottom);
                $this->Cell($this->widthNovedad / 2, $this->lineHeight, $pago_detalle->novedadEn, $border_bottom);
                $total = $pago_detalle->total;
                $this->Cell(0, $this->lineHeight, $total, 'R'.$border_bottom, 1, 'R');
            }else{
                $this->SetFont('Arial','B',$this->fntSize);
                $this->setX($this->getX() + $this->width);
                $this->Cell($this->widthNovedad, $this->lineHeight, 'TOTAL '.$titulo, 1);
                $this->Cell(0, $this->lineHeight, $pago_detalle->total, 1, 1, 'R');
            }
            $i--;
        }
    }

    public function Footer()
    {
        
    }
}