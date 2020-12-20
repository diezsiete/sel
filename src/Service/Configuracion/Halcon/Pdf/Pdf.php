<?php


namespace App\Service\Configuracion\Halcon\Pdf;

use App\Service\Configuracion\Halcon\ServicioEndPoints;
use App\Service\Utils\Varchar;

/**
 * @property Generar generar
 */
class Pdf
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var Varchar
     */
    private $varcharUtil;
    /**
     * @var ServicioEndPoints[]
     */
    private $endpoints = [];

    public function __construct(string $url, Varchar $varcharUtil)
    {
        $this->url = $url . '/pdf';
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        if(!isset($this->endpoints[$name])) {
            switch ($name) {
                case 'generar':
                    $this->endpoints[$name] = new Generar($this->url, $this->varcharUtil);
                    break;
            }
        }
        return $this->endpoints[$name];
    }

}