<?php


namespace App\Service\Pdf\Halcon;


use App\Service\Pdf\PdfBase;

abstract class HalconPdfBase extends PdfBase
{
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w, $h, $this->fpdftxt($txt), $border, $ln, $align, $fill, $link);
    }

    private function fpdftxt($txt){
        if(function_exists('iconv'))
            return  iconv('UTF-8', 'windows-1252', $txt);
        return $txt;
    }
}