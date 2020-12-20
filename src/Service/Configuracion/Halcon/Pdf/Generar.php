<?php


namespace App\Service\Configuracion\Halcon\Pdf;


use App\Service\Configuracion\Halcon\ServicioEndPoints;
use App\Service\Utils\Varchar;

/**
 * @property string html
 * @property string htmlLeerBorrar
 */
class Generar extends ServicioEndPoints
{
    protected $nameGlue = '/';

    public function __construct(string $url, Varchar $varcharUtil)
    {
        parent::__construct($url . '/generar', $varcharUtil);
    }
}