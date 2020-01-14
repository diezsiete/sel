<?php


namespace App\Service\Pdf;


use App\Service\Configuracion\Compania;
use App\Service\Configuracion\Configuracion;
use App\Service\Utils;
use FPDF;

abstract class PdfBase extends FPDF
{
    /**
     * @var Configuracion
     */
    protected $configuracion;
    /**
     * @var Compania
     */
    protected $compania;

    protected $lineHeight = 10;

    public $customLineHeight = 8;
    public $customFontSize = 12;
    public $customFontFamily = 'Arial';

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
    public function setConfiguracion(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
        $this->compania = $configuracion->getCompania($this->configuracion->getEmpresa());
    }

    /**
     * @required
     */
    public function setUtils(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @param string $companiaName
     * @return $this
     */
    public function setCompania($companiaName)
    {
        $this->compania = $this->configuracion->getCompania($companiaName);
        return $this;
    }

    public function getWritableWidth(){
        return $this->GetPageWidth() - $this->lMargin - $this->rMargin;
    }

    public function getLMargin(){
        return $this->lMargin;
    }

    public function Header()
    {
        $logoImg = $this->configuracion->getLogoPdf();
        $imageWidth = 38;

        $this->Image($logoImg ,$this->GetX() + 149, $this->getY() + 3, $imageWidth);
        $this->Cell(0, $this->lineHeight + 15 , '', 0, 1);
    }

    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $compania = $this->compania->getRazon();
        $compania_dir = $this->compania->getDir();
        $compania_web = $this->compania->getWeb();

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

    public function bold()
    {
        $this->SetFont($this->customFontFamily,'B',$this->customFontSize);
    }

    public function unBold()
    {
        $this->SetFont($this->customFontFamily,'',$this->customFontSize);
    }

    public function setFontSize($fontSize)
    {
        $this->customFontSize = $fontSize;
        $this->SetFont($this->customFontFamily,'',$this->customFontSize);
    }

    public function getRMargin(){
        return $this->GetPageWidth() - $this->rMargin;
    }

}