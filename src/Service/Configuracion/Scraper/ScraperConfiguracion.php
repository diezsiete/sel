<?php

namespace App\Service\Configuracion\Scraper;


use App\Service\Configuracion\Configuracion;

class ScraperConfiguracion
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Novasoft
     */
    private $novasoft;

    /**
     * @var Ael
     */
    private $ael;


    public function __construct(Configuracion $configuracion, $config, $empresaConfig)
    {
        $this->configuracion = $configuracion;
        $this->url = $config['url'];
        $this->novasoft = new Novasoft($config['novasoft'], $empresaConfig['novasoft']);
        $this->ael = new Ael($config['ael']);
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

    /**
     * @return Ael
     */
    public function getAel(): Ael
    {
        return $this->ael;
    }
}