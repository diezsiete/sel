<?php


namespace App\Service\Pdf;

use App\Entity\Evaluacion\Progreso;

class EvaluacionCertificado extends PdfBase
{
    protected $lineHeight = 8;

    public function render(Progreso $progreso)
    {
        $nombreCompleto = $progreso->getUsuario()->getNombreCompleto();
        $cedula = $progreso->getUsuario()->getIdentificacion();
        $compania = $this->configuracion->getRazon();
        $web = $this->configuracion->getWeb();

        $line_height = $this->lineHeight;
        
        $font_size = 12;
        
        $this->AddPage();
        $this->SetFont('Arial', '', $font_size);


        $this->bold();
        $this->Cell(0, $line_height, $compania, 0, 1, 'C');
        $this->Cell(0, $line_height, 'CERTIFICA QUE:', 0, 1, 'C');
        $this->Ln();

        $this->Cell(0, $line_height, $nombreCompleto, 0, 1, 'C');
        $this->unBold();
        $this->MultiCell(round($this->getWritableWidth()), $line_height - 2, "Identificado(a) con C.C $cedula", 0, 'C');

        $this->Ln();

        $text = utf8_decode('Realizó') . ' en la fecha ' . $progreso->getCulminacion()->format('d/m/Y') . ' y ' . utf8_decode('aprobó')
            . ' con una  ' . utf8_decode('calificación') . 'de 100/100, la ' . utf8_decode('Inducción') . '/' . utf8_decode('Reinducción')
            . ' Corporativa y de Seguridad y Salud en el Trabajo, la cual ' . utf8_decode('incluyó') . ' los siguientes temas:';

        $this->MultiCell(round($this->getWritableWidth()), $line_height - 2, $text, 0, 'C');
        $this->Ln();
        $this->Ln();


        $this->setFontSize($this->customFontSize - 3);

        $this->bold();
        $this->Cell(0, $line_height, utf8_decode('Módulo') . ' 1 ' . utf8_decode('INDUCCIÓN') . ' CORPORATIVA:', 0, 1, 'L');
        $this->unBold();

        $text = 'Nuestra empresas, ' . utf8_decode('misión')
            . ', ' . utf8_decode('visión') . ', ' . utf8_decode('política') . ' de calidad, cobertura, redes sociales,'
            . ' recomendaciones generales,  bienestar (convenios), ' . utf8_decode('página') . ' web,  copasst, ' . utf8_decode('comité')
            . ' de convivencia.';
        $this->MultiCell(round($this->getWritableWidth()), $line_height - 3, $text, 0, 'J');

        $this->Ln();

        $this->bold();
        $this->Cell(0, $line_height, utf8_decode('Módulo') . ' 2 ' . utf8_decode('INDUCCIÓN') . ' DE SEGURIDAD Y SALUD EN EL TRABAJO:', 0, 1, 'L');
        $this->unBold();

        $text = 'SG-SST Sistema De ' . utf8_decode('Gestión') . ' De La Seguridad y Salud En El Trabajo, ' . utf8_decode('definición')
            . ' de SST, contenido del SG-SST, ' . utf8_decode('Política') . ' de SST, ' . utf8_decode('Política') . ' de Seguridad Vial, '
            . utf8_decode('Política') . ' de ' . utf8_decode('prevención') . ' de consumo de sustancias psicoactivas,  Objetivos del SG-SST,'
            . ' Reglamento Interno de Trabajo, Reglamento de Higiene y Seguridad Industrial, derechos y deberes de los empleados '
            . ' y del empleador,  responsabilidades de los trabajadores frente al SG-SST, Sistema de Seguridad Social Integral'
            . ' (EPS, AFP, ARL), Riesgos Laborales (enfermedad laboral, incidente y accidente de trabajo), procedimiento en'
            . ' caso de accidente de trabajo, prestaciones asistenciales y ' . utf8_decode('económicas') . ' por riesgos laborales, '
            . utf8_decode('Notificación') . ' de Peligros y ' . utf8_decode('cómo') . ' evitarlos (normas de seguridad) y'
            . ' Seguridad Vial - Tips de Seguridad.';
        $this->MultiCell(round($this->getWritableWidth()), $line_height - 3, $text, 0, 'J');

        $this->Ln();

        $text = 'Se ' . utf8_decode('informó') . ' sobre COVID-19 (Coronovirus) Pandemia Mundial, (Signos y Sintomas;Medidas de ' . utf8_decode('prevención')
            . ' y como disminuir el riesgo de contagio; Procedimiento en casos sospechoso y confirmados de Covid -19 calificado en primera oportunidad por la ARL SURA)';
        $this->MultiCell(round($this->getWritableWidth()), $line_height - 3, $text, 0, 'J');

        $this->Ln();

        $this->bold();
        $this->Cell(0, $line_height, utf8_decode('Módulo') . ' 3 ' . utf8_decode('CAPÍTULO') . ' DE ACOSO LABORAL - REGLAMENTO INTERNO DE TRABAJO:', 0, 1, 'L');
        $this->unBold();
        $text = 'Normatividad y conceptos '.utf8_decode('básicos').' relacionados con Acoso Laboral, conductas que se '
            . ' consideran acoso laboral, conductas atenuantes, conductas agravantes, conductas que no constituyen acoso laboral'
            . ', conductas que constituyen acoso laboral, tratamiento sancionatorio, mecanismos para la '. utf8_decode('prevención')
            . ' de conductas de acoso, procedimiento interno para la '.utf8_decode('recepción').', manejo y '.utf8_decode('solución')
            . ' de los casos de acoso laboral, correo '.utf8_decode('electrónico').' dispuesto para la '.utf8_decode('recepción')
            . ' de quejas por presunto acoso laboral.';
        $this->MultiCell(round($this->getWritableWidth()), $line_height - 3, $text, 0, 'J');

        $this->Ln();

        $this->setFontSize($this->customFontSize + 2);

        $this->Cell(0, $line_height,
            'La presente ' . utf8_decode('certificación') . ' se expide por la ' . utf8_decode('página') . ' '.$web.' al interesado.',
            0, 1, 'C');



        $this->Cell(0, $line_height,
            'Dado en ' . utf8_decode('Bogotá') . ' a los ' . date('d') . ' ' . utf8_decode('días') . ' del mes de '
            . $this->utils->meses(date('n') - 1) . ' del ' . date('Y')
            , 0, 1, 'C'
        );

        $this->Ln();

        $declaroText = 'Declaro bajo la gravedad de juramento que he sido yo ____________________________________'
            . ', identificado con CC ______________ de _______________, quien ' . utf8_decode('realizó')
            . ' la ' . utf8_decode('inducción') . '/' . utf8_decode('reinducción') . ' y ' . utf8_decode('respondió')
            . ' cada una de las preguntas realizadas por la empresa '.$compania;

        $declaroWidth = round(($this->getWritableWidth() / 3) * 2);
        $this->MultiCell($declaroWidth, $line_height - 3, $declaroText);
        return $this->Output();
    }
}