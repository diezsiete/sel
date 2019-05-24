<?php
use App\Service\NovasoftSsrs\Entity\NovasoftCertificadoLaboral;
use App\Service\Pdf\PdfCartaLaboral;

/** @var PdfCartaLaboral $pdf */
/** @var NovasoftCertificadoLaboral $certificado */

$compania = 'pta';

$eusuaria = $certificado->getEmpresaUsuaria();
$cargo    = $certificado->getCargo();
$desde    = $certificado->getFechaIngresoTextual();
$nsalario = $certificado->getNsalario();
$salario  = $certificado->getSalario();
$sex      = $certificado->esHombre();
$fecing   = $certificado->getFechaIngreso()->format('Y-m-d');
$fecegr   = $certificado->getFechaEgreso() ? $certificado->getFechaEgreso()->format('Y-m-d') : null;

//logo y no empresa usuario no se muestra para usuario universal
$show_empresa_usuaria = true;
//$logo_img = \SE\SE::getRaiz().$info['logo_pdf'];
$nit      = 666;
// $firma_img = \SE\SE::getRaiz().$info['certificado_laboral']['firma_img'];

$main = "".($sex ? 'El':'La').utf8_decode(" señor").($sex?'':'a')." " . $certificado->getNombreCompleto()
    . " identificad".($sex?'o':'a')." con "
    . utf8_decode($certificado->getTipoDocumento()) . " No. " . $certificado->getCedula() . "";
if ($certificado->isActivo()) {
    $main .= " se encuentra vinculad". ($sex ? 'o' : 'a') . " laboralmente con la ".utf8_decode("Compañía")
        . " $compania en un contrato por ". $certificado->getContrato() . " como trabajador en ".utf8_decode("misión")
        . " en la Empresa Usuaria $eusuaria ".utf8_decode("desempeñando")." el cargo de"
        . " $cargo desde el $desde con una ".utf8_decode("asignación")." salarial mensual de $nsalario ($$salario)";

} else {
    $main .= " ".utf8_decode("laboró")." por medio de contrato de ".$certificado->getContrato()." ".utf8_decode("desempeñando")." el cargo de $cargo"
        . " para la Empresa Usuaria $eusuaria en los siguientes periodos:\n\n"
        . "Fecha Ingreso : " . $fecing ." \n"
        . "Fecha Retiro   : " . $fecegr;
}


$logo_height = 0;
$line_height = 8;
$font_size   = 12;
$image_width = 38;
$firma_img_width = 58;

$pdf->AddPage();
$pdf->SetFont('Arial','',$font_size);
// $pdf->Image($logo_img ,$pdf->GetX(), $pdf->getY() + 3, $image_width);

$pdf->Cell(0, $line_height+ $logo_height , '', 0, 1);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('Arial','B',$font_size);
$pdf->Cell(0, $line_height, $compania, 0, 1, 'C');
$pdf->Cell(0, $line_height, $nit, 0, 1, 'C');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0, $line_height, 'CERTIFICA QUE:', 0, 1, 'C');
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('Arial','',$font_size);


$pdf->MultiCell(round($pdf->getWritableWidth()), $line_height - 2, $main, 0, 'J');


$pdf->SetFont('Arial','',$font_size);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

//$pdf->Cell(0, $line_height,
//    'Dado en '.utf8_decode('Bogotá').' a los '.date('d').' '.utf8_decode('días').' del mes de '
//    . SE::inject('SE.UtilsService')->meses(date('n')-1) . ' del '. date('Y')
//    , 0, 1
//);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0, $line_height, 'Atentamente, ', 0, 1);
$pdf->Ln();
// $pdf->Image($firma_img , $pdf->getLMargin() + 3, $pdf->getY(), $firma_img_width + 15);
$pdf->Ln();
$pdf->Ln();

$pdf->Cell(0, $line_height-2, 'FERNANDA SANCHEZ LAGUNA ', 0, 1);
$pdf->Cell(0, $line_height-2, 'Directora de Servicio al Cliente', 0, 1);
$pdf->Cell(0, $line_height-2, 'PBX 756 98 40 Ext. 110-106-136', 0, 1);


$pdf->Output();