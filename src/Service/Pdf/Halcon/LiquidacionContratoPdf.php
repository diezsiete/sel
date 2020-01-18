<?php


namespace App\Service\Pdf\Halcon;

use App\Entity\Halcon\CabezaLiquidacion;


class LiquidacionContratoPdf extends HalconPdfBase
{

    public function build(CabezaLiquidacion $cabeza)
    {
        $compania_nombre = $cabeza->getVinculacion()->getEmpresa()->getCompania()->getNombre();
        $compania_nit = $cabeza->getVinculacion()->getEmpresa()->getCompania()->getNit();

        $line_height = 5;
        $font_size   = 10;
        $image_width = 38;
        $border = 0;
        $width = 100;
        $width2 = 50;


        $logo = $this->compania->getLogoPdf();


        $this->AddPage();
        $this->Image($logo ,$this->getX() + 1, $this->getY() - 3, $image_width);

        $this->SetFont('Arial','B',$font_size + 6);
        $this->Cell(200, $line_height, 'LIQUIDACION DE CONTRATO DE TRABAJO', $border, 0, 'R');
        $this->SetFont('Arial','B',$font_size);
        $this->Ln();
        $this->Ln();
        $this->Ln();

        //Datos Empresa
        $this->Cell(100, $line_height, "Cod. contrato : " . $cabeza->getVinculacion()->getNoContrat(),$border, 0);
        $this->Cell(0, $line_height, "Fecha : " . date("d-m-Y"), $border, 0, 'R');
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height, $cabeza->getCodCia() . ' ' . $cabeza->getNomCia(), $border, 1);
        $this->Cell(0, $line_height, "Nit : " . $compania_nit, $border, 1);

        //Datos empleado
        $this->Ln();
        $this->SetFont('Arial','',$font_size-1);
        $this->Cell($width, $line_height, "Nombre : " . $cabeza->getNomEmplea(), $border, 0);
        $this->Cell($width2, $line_height, "Documento : ", $border, 0, 'R');
        $this->Cell(0, $line_height,  $cabeza->getAuxiliar(false), $border, 1, 'R');
        $this->Cell($width, $line_height, "Cargo : " . $cabeza->getNomCargo(), $border, 0);
        $this->Cell($width2, $line_height, "Fecha de inicio y terminación : ", $border, 0, 'R');
        $this->Cell(0, $line_height, $cabeza->getFecha(),$border, 1, 'R');
        $this->Cell($width, $line_height, "Banco : " . $cabeza->getBanco()->getNombre(), $border, 0);
        $this->Cell($width2, $line_height, "Cuenta : ", $border, 0, 'R');
        $this->Cell(0, $line_height, $cabeza->getCtaBanco(), $border, 1, 'R');
        $this->Cell($width, $line_height, "Tiempo de servicio : " . $cabeza->getDiasEmp() . " dias", $border, 1);
        $this->Cell(39, $line_height, "El contrato se termina por : ", $border, 0);
        $this->MultiCell(0, $line_height, $cabeza->getXtermina(), $border);

        //Salario base de liquidación
        $width = 60;
        $width2= 50;
        $width3= 50;

        $this->Ln();
        $this->SetFont('Arial','B',$font_size +1);
        $this->Cell(0, $line_height + 3, "SALARIOS BASE DE LIQUIDACIÓN", 1, 1, 'C');
        $this->SetFont('Arial','B',$font_size -1);
        $this->Cell($width, $line_height, "CONCEPTO NOMINA", 'LB', 0);
        $this->Cell($width2, $line_height, "CESANTIAS", 'B', 0, 'R');
        $this->Cell($width3, $line_height, "PRIMA DE SERVICIO", 'B', 0, 'R');
        $this->Cell(0, $line_height, "VACACIONES", 'RB', 1, 'R');

        $this->SetFont('Arial','',$font_size -2);
        $this->Cell($width, $line_height, "Sueldo", 'L', 0);
        $this->Cell($width2,$line_height, $cabeza->getSuelCes(), 0, 0, 'R');
        $this->Cell($width3,$line_height, $cabeza->getSuelPri(), 0, 0, 'R');
        $this->Cell(0,$line_height, $cabeza->getSuelVac(), 'R', 1, 'R');
        $this->Cell($width, $line_height, "Aux. Transporte", 'L', 0);
        $this->Cell($width2,$line_height, $cabeza->getAuxCes(), 0, 0, 'R');
        $this->Cell($width3,$line_height, $cabeza->getAuxPri(), 0, 0, 'R');
        $this->Cell(0,$line_height, $cabeza->getAuxVac(), 'R', 1, 'R');
        $this->Cell($width, $line_height, "Recargo nocturno", 'L', 0);
        $this->Cell($width2,$line_height, $cabeza->getRecCes(), 0, 0, 'R');
        $this->Cell($width3,$line_height, $cabeza->getRecPri(), 0, 0, 'R');
        $this->Cell(0,$line_height, $cabeza->getRecVac(), 'R', 1, 'R');
        $this->Cell($width, $line_height, "Horas extra", 'L', 0);
        $this->Cell($width2,$line_height, $cabeza->getExtCes(), 0, 0, 'R');
        $this->Cell($width3,$line_height, $cabeza->getExtPri(), 0, 0, 'R');
        $this->Cell(0,$line_height, $cabeza->getExtVac(), 'R', 1, 'R');
        $this->Cell($width, $line_height, "Comisiones", 'L', 0);
        $this->Cell($width2,$line_height, $cabeza->getComCes(), 0, 0, 'R');
        $this->Cell($width3,$line_height, $cabeza->getComPri(), 0, 0, 'R');
        $this->Cell(0,$line_height, $cabeza->getComVac(), 'R', 1, 'R');
        $this->Cell($width, $line_height+2, "TOTAL BASE", 'L', 0);
        $this->Cell($width2,$line_height+2, $cabeza->getBaseCes(), '', 0, 'R');
        $this->Cell($width3,$line_height+2, $cabeza->getBasePri(), '', 0, 'R');
        $this->Cell(0,$line_height+2, $cabeza->getBaseVac(), 'R', 1, 'R');
        $this->Cell(0,$line_height-3, " ", "LRB",1);
        $this->SetFont('Arial','B',$font_size);

        $this->Cell(0,$line_height-1, " ", "LR",1);
        $this->MultiCell(0, $line_height,
            "El suscrito trabajador declara que ha recibido a entera satisfacción de {$compania_nombre} La suma de dinero a que hace referencia la siguiente liquidación:", "LR");
        $this->Cell(0,$line_height-1, " ", "LR",1);
//Renglones

        $this->SetFont('Arial','B',$font_size -1);
        $this->Cell($width, $line_height, "CONCEPTO", 'TLB', 0);
        $this->Cell($width2, $line_height, "NOVEDAD", 'TB', 0, 'R');
        $this->Cell($width3, $line_height, "DEVENGADO", 'TB', 0, 'R');
        $this->Cell(0, $line_height, "DEDUCIDO", 'TRB', 1, 'R');
        $this->SetFont('Arial','',$font_size -2);
        foreach($cabeza->getRenglones() as $r){
            $this->Cell($width, $line_height, $r->getNomConcep(), 'L', 0);
            $this->Cell($width2, $line_height, $r->getNovedad(), 0, 0, 'R');
            $this->Cell($width3, $line_height, $r->getDevengado(), 0, 0,'R');
            $this->Cell(0, $line_height, $r->getDeducido(), 'R', 1,'R');
        }
        $this->Cell(0,$line_height-1, " ", "LR",1);
        $this->SetFont('Arial','B',$font_size-1);
        $this->Cell($width, $line_height, "TOTALES : ", 'TLB', 0, 'R');
        $this->Cell($width2, $line_height, "", 'TB', 0, 'R');
        $this->Cell($width3, $line_height, $cabeza->getTotDeveng(), 'TB', 0, 'R');
        $this->Cell(0, $line_height, $cabeza->getTotDeduci(), 'TRB', 1, 'R');

        $this->SetFont('Arial','B',$font_size);
        $this->Cell(0,$line_height-3, " ", "LR",1);
        $this->Cell($width, $line_height, "NETO : ", 'L', 0, 'R');
        $this->Cell($width2, $line_height, "", 0, 0, 'R');
        $this->Cell($width3, $line_height, "", 0, 0, 'R');
        $this->Cell(0, $line_height, $cabeza->getNeto(), 'R', 1, 'R');
        $this->Cell(0,$line_height-3, " ", "LRB",1);

        //constancia
        $line_height -= 1;
        $this->Ln();
        $this->Ln();
        $this->Cell(0, $line_height, "CONSTANCIA : Se hace constar expresamente lo siguiente:");
        $this->Ln($line_height +1);
        $this->SetFont('Arial','',$font_size-2);
        $this->MultiCell(0, $line_height,
            "Que el patrono ha incorporado en la anterior liquidacion, en lo pertinente, la totalidad de los "
            . "valores correspondientes salario, horas extras recargos por trabajo nocturno, descansos remunerados, cesantía, vacaciones, auxilio por "
            . "enfermedad, accidente de trabajo, prima, calzado y overoles, auxilio de transporte y en general todo concepto "
            . "relacionado con salarios, prestaciones o indemnizaciones que tengan por causa el contrato de trabajo que ha quedado extinguido."
        );
        $this->Ln($line_height -2);
        $this->MultiCell(0, $line_height, "En consideracion a que la obtención de los datos contables, elaboración y revisión de la presente liquidación, su aprobación y el giro de cheques, ha exigido varios días, por lo cual, ha sido físicamente imposible para en el instante de la terminación del contrato, el trabajador conviene expresamente en que término transcurrido entre la terminación del contrato y la fecha de esta liquidación y pago ha sido necesaria y razonable para estos efectos y que, en consecuencia, no ha habido mora en el pago.");
        $this->Ln($line_height -2);
        $this->MultiCell(0, $line_height, "Que no obstante la anterior declaración consta por las partes que con el pago de la suma de dinero a que hace referencia la presente liquidación, queda transada cualquier diferencia relativa al contrato de trabajo que ha quedado terminado, pues ha sido su común ánimo transar definitivamente, como en efecto se transa, todo reclamo pasado, presente o futuro que tenga por causa del mencionado contrato. Por consiguiente esta transacción tiene como efecto la extinción de las obligaciones provenientes de la relación laboral que existió entre el empleador y trabajador quienes recíprocamente se declaran a paz y salvo por los conceptos expresados, excepto en cuanto a derechos ciertos e indiscutibles del trabajador que por cualquier circunstancia, esten pendientes de reconocimiento o pago (Art.15 C.S.T)");
        return $this;
    }


    public function Header()
    {

    }

    public function Footer()
    {

    }



}