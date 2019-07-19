<?php


namespace App\Service\Configuracion;


class ScrapperConfiguracion
{
    public $url;

    public function __construct($config)
    {
        $this->url = $config['url'];
    }
}