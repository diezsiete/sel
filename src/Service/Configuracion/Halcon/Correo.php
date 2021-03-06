<?php


namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;

/**
 * @property string enviar
 * @property string adjuntoLimite
 */
class Correo extends ServicioEndPoints
{
    public function __construct(string $url, Varchar $varcharUtil)
    {
        parent::__construct($url . '/correo', $varcharUtil);
    }
}