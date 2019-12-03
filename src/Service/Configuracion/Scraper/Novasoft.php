<?php


namespace App\Service\Configuracion\Scraper;


class Novasoft
{
    /**
     * @var string
     */
    private $conexion;
    private $browser;

    public function __construct($config, $empresaConfig)
    {
        $this->conexion = $config['conexion'];
        $this->browser = $empresaConfig['browser'];
    }

    /**
     * @return string
     */
    public function getConexion(): string
    {
        return $this->conexion;
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    

}