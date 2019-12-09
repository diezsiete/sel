<?php


namespace App\Service\Pdf;


class PdfCartaLaboralServilabor extends PdfCartaLaboral
{
    protected $firmaImageWidth = 43;

    protected function contentActivo($sex, $companiaNombre, $contratoTermino, $cargo, $desde, $salarioTexto, $salarioNum, $eusuaria = null)
    {
        return " se encuentra vinculad". ($sex ? 'o' : 'a') . " laboralmente con la ".utf8_decode("Compañía")
            . " $companiaNombre en un contrato por ". $contratoTermino ." ".utf8_decode("desempeñando")." el cargo de"
            . " $cargo desde el $desde con una ".utf8_decode("asignación")." salarial mensual de $salarioTexto ($$salarioNum)";
    }

    protected function contentInactivo($contratoTermino, $cargo, $fecing, $fecegr, $eusuaria = null, $companiaNombre = null)
    {

        return " " . utf8_decode("laboró") . " por medio de contrato de " . $contratoTermino . " "
            . utf8_decode("desempeñando") . " el cargo de $cargo"
            . " en la ".utf8_decode("Compañía")." $companiaNombre en los siguientes periodos:\n\n"
            . "Fecha Ingreso : " . $fecing . " \n"
            . "Fecha Retiro   : " . $fecegr;
    }

    protected function firma()
    {
        $this->Image(
            $this->configuracion->certificadoLaboral()->getFirma(),
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