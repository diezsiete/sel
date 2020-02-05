<?php


namespace App\Service\Pdf\Halcon;

use App\Entity\Halcon\Report\CertificadoLaboral;
use App\Entity\Halcon\Tercero;
use App\Service\Pdf\Util\HtmlTable2\PdfHtmlTable;
use DateTimeInterface;

class CertificadoLaboralServilaborPdf extends CertificadoLaboralPdf
{
    protected $firmaImageWidth = 37;
    protected function firma()
    {
        $this->Image(
            $this->configuracion->certificadoLaboral()->getFirma(),
            $this->getLMargin() + 3,
            $this->getY() - 10,
            $this->firmaImageWidth
        );

        $this->Ln();
        $this->Ln();
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getFirmante(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getCargo(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getContacto(), 0, 1);

        return $this;
    }

}