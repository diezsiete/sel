<?php


namespace App\Service\Pdf;


use App\Service\SelParameters;
use App\Service\Utils;
use Symfony\Component\Asset\Packages;

abstract class PdfBase extends \FPDF
{
    /**
     * @var SelParameters
     */
    protected $parameters;

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
     * @param SelParameters $parameters
     */
    public function setSelParameters(SelParameters $parameters)
    {
        $this->parameters = $parameters;
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
        $compania = $this->parameters->getRazon();
        $compania_dir = $this->parameters->getDir();
        $compania_web = $this->parameters->getWeb();

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