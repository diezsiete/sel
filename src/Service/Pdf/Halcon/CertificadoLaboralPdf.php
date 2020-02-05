<?php


namespace App\Service\Pdf\Halcon;

use App\Entity\Halcon\Report\CertificadoLaboral;
use App\Entity\Halcon\Tercero;
use App\Service\Pdf\Util\HtmlTable2\PdfHtmlTable;
use DateTimeInterface;

class CertificadoLaboralPdf extends HalconPdfBase
{
    use PdfHtmlTable;

    protected $firmaImageWidth = 73;

    /**
     * @var CertificadoLaboral
     */
    protected $certificadoLaboral;

    protected static $colsMapping = [
        "Empresa Usuaria" => 'convenio',
        'Cargo o Labor' => 'cargo',
        'Contrato' => 'contrato',
        'Inicio' => 'fechaIngreso',
        'Terminacion' => 'fechaRetiro',
        'Salario Contratación' => 'salario'
    ];

    public function Header()
    {
        $logoImg = $this->compania->getLogoPdf();
        //universal servilabor
        if($this->certificadoLaboral->usuario == 'AW' && $this->configuracion->getCompanias()) {
            $compania = $this->configuracion->getCompanias()['UNIVERSAL'];
            $logoImg = $compania->getLogoPdf();
        }

        $imageWidth = 38;

        $this->Image($logoImg ,$this->GetX() + 149, $this->getY() + 3, $imageWidth);
        $this->Cell(0, $this->lineHeight + 15 , '', 0, 1);
    }

    public function build(CertificadoLaboral $certificadoLaboral, Tercero $tercero)
    {
        $this->certificadoLaboral = $certificadoLaboral;
        $nombre  = trim($tercero->getNombre());
        $sx    = strtoupper($tercero->getSexo()) == 'F' ? 0 : 1;
        $nit_tercer = $tercero->getNitTercer();

        $line_height = 8;
        $font_size   = 12;

        $this->AddPage();
        $this->SetFont('Arial','',$font_size);


        $this->Cell(0, 1, 'Nit : ' . $this->compania->getNit(), 0, 1, 'R');


        $this->Ln(30);
        $this->SetFont('Arial','B',$font_size);
        $this->Cell(0, $line_height, 'CERTIFICA QUE', 0, 1, 'C');
        $this->SetFont('Arial','',$font_size);
        $this->Ln();
        $this->Ln();

        $this->MultiCell(0,$line_height,
            ($sx ? 'El señor ' : 'La señora ') . $nombre . ' identificad' . ($sx ? 'o' : 'a') .
            ' con la cédula de ciudadanía No. '. $nit_tercer .' labora(ó) para nuestra compañia' .
            ' con Contrato de Duración a Termino de obra o labor así:', 0, 1);

        $this->SetFont('Arial','',8);
        $this->WriteHTML($this->htmlTable($certificadoLaboral));


        $this->SetFont('Arial','',$font_size);
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height,
            'Dado en Bogotá a los '.date('d').' días del mes de '
            . $this->utils->meses(date('n')-1) . ' del '. date('Y')
            , 0, 1
        );


        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height, 'Atentamente, ', 0, 1);
        $this->Ln();

        return $this->firma();
    }

    private function htmlTable(CertificadoLaboral $certificadoLaboral, $showEmpresaUsuaria = true)
    {
        $html = "<table><tr>";
        foreach(array_keys(static::$colsMapping) as $col) {
            if(!$showEmpresaUsuaria && $col === 'Empresa Usuaria') {
                continue;
            }
            $html .= '<td>' . $col . '</td>';
        }
        $html .= '</tr>';

        $html .= '<tr>';
        foreach(static::$colsMapping as $col => $field) {
            if(!$showEmpresaUsuaria && $col === 'Empresa Usuaria') {
                continue;
            }
            $value = $certificadoLaboral->$field;
            if(is_object($value) && $value instanceof DateTimeInterface) {
                $value = $value->format('Y-m-d');
            }
            $html .= '<td>' . $value . '</td>';
        }
        $html .= '</tr></table>';

        return $html;
    }

    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $compania = $this->compania->getRazon();
        $compania_dir = $this->compania->getDir();
        $compania_web = $this->compania->getWeb();

        $this->SetY(-20);
        $this->SetFont('Arial','',7);
        $txt1 = 'LA INFORMACIÓN CONTENIDA EN LA PRESENTE CERTIFICACIÓN PROVIENE DE LA ' .
            'BASE DE DATOS DE ' . strtoupper($compania);

        $this->MultiCell(round($this->getWritableWidth()),2, $txt1, 0, 'C');


        $this->Ln();
        $this->Ln();
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $this->getWritableWidth(), $this->GetY());
        // Arial italic 8

        // Page number
        $txt3 = $compania . ". Bogotá : " . $compania_dir . ". " . $compania_web;
        $this->Cell(0,10, $txt3,0,0,'C');
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