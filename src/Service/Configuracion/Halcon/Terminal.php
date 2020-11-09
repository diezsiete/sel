<?php


namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;

/**
 * @property string ejecutar
 */
class Terminal extends ServicioEndPoints
{
    public function __construct(string $url, Varchar $varcharUtil)
    {
        parent::__construct($url . '/terminal', $varcharUtil);
    }
}