<?php


namespace App\Service\ServicioEmpleados\Report;


interface ReportInterface
{
    public function renderPdf();

    public function streamPdf();

    public function linkPdf();

    public function getPdfFileName(): string;

    public function getImporter(): ImporterInterface;
}