<?php

namespace App\Service\Configuracion\Scraper;


class ScraperConfiguracion
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var Novasoft
     */
    private $novasoft;


    public function __construct($config, $empresaConfig)
    {
        $this->url = $config['url'];
        $this->novasoft = new Novasoft($config['novasoft'], $empresaConfig['novasoft']);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * @return Novasoft
     */
    public function getNovasoft(): Novasoft
    {
        return $this->novasoft;
    }
}