<?php


namespace App\Service\Pdf;


abstract class PdfBase extends \FPDF
{
    public function getWritableWidth(){
        return $this->GetPageWidth() - $this->lMargin - $this->rMargin;
    }

    public function getLMargin(){
        return $this->lMargin;
    }
}