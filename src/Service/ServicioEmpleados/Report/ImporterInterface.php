<?php


namespace App\Service\ServicioEmpleados\Report;


interface ImporterInterface
{
    public function importMap();

    public function importPdf();

    public function deletePdf();

    public function importMapAndPdf();
}