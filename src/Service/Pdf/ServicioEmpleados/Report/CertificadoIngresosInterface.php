<?php


namespace App\Service\Pdf\ServicioEmpleados\Report;


interface CertificadoIngresosInterface
{
    public function getNumeroFormulario();

    public function getNit(): string;

    public function getDv();

    public function getRazonSocial(): string;

    public function getTipoDocumento();

    public function getIdentificacion();

    public function getPrimerApellido();

    public function getSegundoApellido();

    public function getPrimerNombre();

    public function getSegundoNombre();

    public function getFechaInicial(): \DateTimeInterface;

    public function getFechaFinal(): \DateTimeInterface;

    public function getFechaExpedicion(): \DateTimeInterface;

    public function getCiudad();

    public function getCiudadCodigo();

    public function getDepartamentoCodigo();

    public function getMonto($property);

    public function getIngresoProperties(): array;

    public function getIngresoTotal();

    public function getAportesProperties(): array;
}