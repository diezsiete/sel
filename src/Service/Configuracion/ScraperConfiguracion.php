<?php


namespace App\Service\Configuracion;


use StdClass;

class ScraperConfiguracion
{
    public $url;

    private $empresaConfiguracion;

    public function __construct($config, $empresaConfiguracion)
    {
        $this->url = $config['url'];
        $this->empresaConfiguracion = $empresaConfiguracion;
    }

    /**
     * @return StdClass
     */
    public function ael()
    {
        return (object)$this->empresaConfiguracion['ael'];
    }

}