<?php


namespace App\Service\Configuracion\Scraper;


class Novasoft
{
    /**
     * @var string
     */
    private $conexion;

    public function __construct($config)
    {
        $this->conexion = $config['conexion'];
    }

    /**
     * @return string
     */
    public function getConexion(): string
    {
        return $this->conexion;
    }

}