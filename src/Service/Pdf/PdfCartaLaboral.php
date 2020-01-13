<?php


namespace App\Service\Pdf;

use App\Entity\Novasoft\Report\CertificadoLaboral;

class PdfCartaLaboral extends PdfBase
{
    protected $lineHeight = 8;
    protected $firmaImageWidth = 73;

    public function Header()
    {
    }

    public function render(CertificadoLaboral $certificado)
    {
        $compania = $this->compania->getRazon();
        $nit = $this->compania->getNit();
        $logoImg = $this->compania->getLogoPdf();

        $eusuaria = $certificado->getEmpresaUsuaria();
        $cargo = $certificado->getCargo();
        $desde = $certificado->getFechaIngresoTextual();
        $nsalario = $certificado->getNsalario();
        $salario = $certificado->getSalario();
        $sex = $certificado->isHombre();
        $fecing = $certificado->getFechaIngreso()->format('Y-m-d');
        $fecegr = $certificado->getFechaEgreso() ? $certificado->getFechaEgreso()->format('Y-m-d') : null;


        $main = "" . ($sex ? 'El' : 'La') . utf8_decode(" señor") . ($sex ? '' : 'a') . " " . $certificado->getNombreCompleto()
            . " identificad" . ($sex ? 'o' : 'a') . " con "
            . utf8_decode($certificado->getTipoDocumento()) . " No. " . $certificado->getCedula() . "";

        $main .= $certificado->isActivo()
            ? $this->contentActivo($sex, $compania, $certificado->getContrato(), $cargo, $desde, $nsalario, $salario, $eusuaria)
            : $this->contentInactivo($certificado->getContrato(), $cargo, $fecing, $fecegr, $eusuaria, $compania);

        $logo_height = 0;
        $line_height = 8;
        $font_size = 12;
        $image_width = 38;

        $this->AddPage();
        $this->SetFont('Arial', '', $font_size);
        $this->Image($logoImg ,$this->GetX(), $this->getY() + 3, $image_width);

        $this->Cell(0, $line_height + $logo_height, '', 0, 1);
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial', 'B', $font_size);
        $this->Cell(0, $line_height, $compania, 0, 1, 'C');
        $this->Cell(0, $line_height, $nit, 0, 1, 'C');
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height, 'CERTIFICA QUE:', 0, 1, 'C');
        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial', '', $font_size);


        $this->MultiCell(round($this->getWritableWidth()), $line_height - 2, $main, 0, 'J');


        $this->SetFont('Arial', '', $font_size);
        $this->Ln();
        $this->Ln();
        $this->Ln();

        $this->Cell(0, $line_height,
            'Dado en '.utf8_decode('Bogotá').' a los '.date('d').' '.utf8_decode('días').' del mes de '
            . $this->utils->meses(date('n')-1) . ' del '. date('Y')
            , 0, 1
        );
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height, 'Atentamente, ', 0, 1);
        $this->Ln();

        return $this
            ->firma()
            ->Output();
    }

    protected function contentActivo($sex, $companiaNombre, $contratoTermino, $cargo, $desde, $salarioTexto, $salarioNum, $eusuaria = null)
    {
        return " se encuentra vinculad" . ($sex ? 'o' : 'a') . " laboralmente con la " . utf8_decode("Compañía")
            . " $companiaNombre en un contrato por " . $contratoTermino . " como trabajador en " . utf8_decode("misión")
            . " en la Empresa Usuaria $eusuaria " . utf8_decode("desempeñando") . " el cargo de"
            . " $cargo desde el $desde con una " . utf8_decode("asignación") . " salarial mensual de $salarioTexto ($$salarioNum)";
    }

    protected function contentInactivo($contratoTermino, $cargo, $fecing, $fecegr, $eusuaria = null, $companiaNombre = null)
    {
        return " " . utf8_decode("laboró") . " por medio de contrato de " . $contratoTermino . " "
            . utf8_decode("desempeñando") . " el cargo de $cargo"
            . " para la Empresa Usuaria $eusuaria en los siguientes periodos:\n\n"
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
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getFirmante(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getCargo(), 0, 1);
        $this->Cell(0, $this->lineHeight - 2, $this->configuracion->certificadoLaboral()->getContacto(), 0, 1);

        return $this;
    }


}