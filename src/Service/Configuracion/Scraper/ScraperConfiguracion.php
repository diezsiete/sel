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
    private $kernelProjectDir;

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

    /**
     * @var bool
     */
    private $autoConsume;


    public function __construct(Configuracion $configuracion, $kernelProjectDir, $config, $empresaConfig)
    {
        $this->configuracion = $configuracion;
        $this->kernelProjectDir = $kernelProjectDir;
        $this->url = $config['url'];
        $this->autoConsume = $empresaConfig['auto_consume'];
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
     * @return bool
     */
    public function isAutoConsume(): bool
    {
        return $this->autoConsume;
    }

    public function getConsumeCommand()
    {
        $phpExec = $this->configuracion->phpExec();
        return $phpExec . " " . $this->kernelProjectDir . "/bin/messenger messenger:consume scraper_hv async --limit=1";
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