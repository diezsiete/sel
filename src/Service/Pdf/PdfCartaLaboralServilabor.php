<?php


namespace App\Service\Pdf;


class PdfCartaLaboralServilabor extends PdfCartaLaboral
{
    protected $firmaImageWidth = 43;

    protected function firma()
    {
        $this->Image(
            $this->configuracion->certificadoLaboral()->getFirma(true),
            $this->getLMargin() + 3,
            $this->getY(),
            $this->firmaImageWidth
        );
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getFirmante(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getCargo(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getContacto(), 0, 1);

        return $this;
    }
}