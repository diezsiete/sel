<?php


namespace App\Service\Pdf;


class PdfCartaLaboral extends PdfBase
{
    public function Footer()
    {
        // Position at 1.5 cm from bottom

        $compania = "PTA";
        $compania_dir = "Cra 7c # 129 81";
        $compania_web = "www.pa.com.co";

        $this->SetY(-20);
        $this->SetFont('Arial','',7);
        $txt1 = 'LA '.utf8_decode('INFORMACIÓN').' CONTENIDA EN LA PRESENTE '.utf8_decode('CERTIFICACIÓN').' PROVIENE DE LA ' .
            'BASE DE DATOS DE ' . strtoupper($compania);
        //$txt2 = 'SIN FIMA AUTOGRAFA SEGÚN ART.10 DEL DECRETO 836/1991';
        // $this->MultiCellFloat(round($this->getWritableWidth()),4, $txt1, 0, 'C');
        //$this->MultiCellFloat(round($this->getWritableWidth()/2),4, $txt2, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $this->getWritableWidth(), $this->GetY());
        // Arial italic 8

        // Page number
        $txt3 = $compania . ". ".utf8_decode("Bogotá")." : " . utf8_decode($compania_dir) . ". " . $compania_web;
        $this->Cell(0,10,$txt3,0,0,'C');
    }
}