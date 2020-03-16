<?php


namespace App\Service\Utils;


class Fecha
{
    /**
     * @param bool $mesIndex utilizar format n-1
     * @return array|mixed
     */
    public function meses($mesIndex = false)
    {
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        return $mesIndex !== false ? $meses[$mesIndex] : $meses;
    }
}