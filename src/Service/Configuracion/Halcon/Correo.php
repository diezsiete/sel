<?php


namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;

/**
 * @property string enviar
 * @property string adjuntoLimite
 */
class Correo
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var Varchar
     */
    private $varcharUtil;

    public function __construct(string $url, Varchar $varcharUtil)
    {
        $this->url = $url . '/correo';
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        switch ($name) {
            default:
                return $this->url . '/' . $this->varcharUtil->toSnakeCase($name, '-');
        }
    }
}