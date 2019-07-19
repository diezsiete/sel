<?php


namespace App\Service\Pdf;


use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;

class PdfCartaLaboral extends PdfBase
{
    public function render(ReporteCertificadoLaboral $certificado)
    {
        $compania = $this->configuracion->getRazon();
        $nit = $this->configuracion->getNit();
        $logoImg = $this->configuracion->getLogoPdf();
        $firmaImg = $this->configuracion->certificadoLaboral()->getFirma(true);
        $firmante = $this->configuracion->certificadoLaboral()->getFirmante();
        $firmanteCargo = $this->configuracion->certificadoLaboral()->getCargo();
        $firmanteContacto = $this->configuracion->certificadoLaboral()->getContacto();

        $eusuaria = $certificado->getEmpresaUsuaria();
        $cargo = $certificado->getCargo();
        $desde = $certificado->getFechaIngresoTextual();
        $nsalario = $certificado->getNsalario();
        $salario = $certificado->getSalario();
        $sex = $certificado->esHombre();
        $fecing = $certificado->getFechaIngreso()->format('Y-m-d');
        $fecegr = $certificado->getFechaEgreso() ? $certificado->getFechaEgreso()->format('Y-m-d') : null;


        $main = "" . ($sex ? 'El' : 'La') . utf8_decode(" señor") . ($sex ? '' : 'a') . " " . $certificado->getNombreCompleto()
            . " identificad" . ($sex ? 'o' : 'a') . " con "
            . utf8_decode($certificado->getTipoDocumento()) . " No. " . $certificado->getCedula() . "";
        if ($certificado->isActivo()) {
            $main .= " se encuentra vinculad" . ($sex ? 'o' : 'a') . " laboralmente con la " . utf8_decode("Compañía")
                . " $compania en un contrato por " . $certificado->getContrato() . " como trabajador en " . utf8_decode("misión")
                . " en la Empresa Usuaria $eusuaria " . utf8_decode("desempeñando") . " el cargo de"
                . " $cargo desde el $desde con una " . utf8_decode("asignación") . " salarial mensual de $nsalario ($$salario)";

        } else {
            $main .= " " . utf8_decode("laboró") . " por medio de contrato de " . $certificado->getContrato() . " " . utf8_decode("desempeñando") . " el cargo de $cargo"
                . " para la Empresa Usuaria $eusuaria en los siguientes periodos:\n\n"
                . "Fecha Ingreso : " . $fecing . " \n"
                . "Fecha Retiro   : " . $fecegr;
        }


        $logo_height = 0;
        $line_height = 8;
        $font_size = 12;
        $image_width = 38;
        $firma_img_width = 58;

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
        $this->Image($firmaImg , $this->getLMargin() + 3, $this->getY(), $firma_img_width + 15);
        $this->Ln();
        $this->Ln();

        $this->Cell(0, $line_height - 2, $firmante, 0, 1);
        $this->Cell(0, $line_height - 2, $firmanteCargo, 0, 1);
        $this->Cell(0, $line_height - 2, $firmanteContacto, 0, 1);


        return $this->Output();
    }
}