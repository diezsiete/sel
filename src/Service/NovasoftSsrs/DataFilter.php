<?php


namespace App\Service\NovasoftSsrs;


class DataFilter
{
    public function limpiarNombre($nombre)
    {
        $nombre = mb_strtoupper($nombre);
        return preg_replace("/[^A-ZÁÉÍÓÚÑ ]/", "", $nombre);
    }

    public function separarNombre($value)
    {
        $nom_explode = explode(' ', $value);
        $pn = trim($nom_explode[0]);
        $sn = count($nom_explode) > 1 ? trim($nom_explode[1]) : '';
        return [$pn, $sn];
    }

    public function fechaFromNovasoft(?string $fecha): ?\DateTime
    {
        return $fecha ? \DateTime::createFromFormat('d/m/Y', $fecha) : null;
    }
}