<?php


namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;

/**
 * @property Correo correo
 * @property Terminal terminal
 */
class Servicios
{
    /**
     * @var array
     */
    private $params;
    /**
     * @var Varchar
     */
    private $varcharUtil;
    /**
     * @var ServicioEndPoints[]
     */
    private $endpoints = [];

    public function __construct(array $params, Varchar $varcharUtil)
    {
        $this->params = $params;
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        if(!isset($this->endpoints[$name])) {
            switch ($name) {
                case 'correo':
                    $this->endpoints[$name] = new Correo($this->params['url'], $this->varcharUtil);
                    break;
                case 'terminal':
                    $this->endpoints[$name] = new Terminal($this->params['url'], $this->varcharUtil);
                    break;
            }
        }
        return $this->endpoints[$name];
    }
}