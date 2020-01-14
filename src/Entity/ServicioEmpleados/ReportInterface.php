<?php


namespace App\Entity\ServicioEmpleados;


use App\Entity\Main\Usuario;

interface ReportInterface
{
    public function getUsuario(): ?Usuario;
}