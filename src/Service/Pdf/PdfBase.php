<?php


namespace App\Service\Pdf;


use App\Service\Configuracion\Configuracion;
use App\Service\Utils;

abstract class PdfBase extends \FPDF
{
    /**
     * @var Configuracion
     */
    protected $configuracion;

    /**
     * @var Utils
     */
    protected $utils;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
    }

    /**
     * @required
     * @param Configuracion $configuracion
     */
    public function setSelParameters(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @required
     */
    public function setUtils(Utils $utils)
    {
        $this->utils = $utils;
    }

    public function getWritableWidth(){
        return $this->GetPageWidth() - $this->lMargin - $this->rMargin;
    }

    public function getLMargin(){
        return $this->lMargin;
    }


    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $compania = $this->configuracion->getRazon();
        $compania_dir = $this->configuracion->getDir();
        $compania_web = $this->configuracion->getWeb();

        $this->SetY(-20);
        $this->SetFont('Arial','',7);
        $txt1 = 'LA '.utf8_decode('INFORMACIÓN').' CONTENIDA EN LA PRESENTE '.utf8_decode('CERTIFICACIÓN').' PROVIENE DE LA ' .
            'BASE DE DATOS DE ' . strtoupper($compania);

        $this->MultiCell(round($this->getWritableWidth()),2, $txt1, 0, 'C');

        $this->Ln();
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $this->getWritableWidth(), $this->GetY());
        // Arial italic 8

        // Page number
        $txt3 = $compania . ". ".utf8_decode("Bogotá")." : " . utf8_decode($compania_dir) . ". " . $compania_web;
        $this->Cell(0,10,$txt3,0,0,'C');
    }

}