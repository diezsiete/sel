<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use Exception;

class NovasoftScraper
{
    /**
     * @var ScraperClient
     */
    protected $scraperClient;
    /**
     * @var Configuracion
     */
    protected $configuracion;

    public function __construct(Configuracion $configuracion, ScraperClient $scraperClient)
    {
        $this->scraperClient = $scraperClient;
        $this->configuracion = $configuracion;
    }

    public function home()
    {
        return $this->scraperClient->getStream($this->getFullUrl('home'));
    }

    /**
     * @param string $url
     * @param bool $hvPrefix
     * @return string
     */
    protected function getFullUrl(string $url = '') {
        return '/' . $this->configuracion->getScraper()->getNovasoft()->getBrowser()
            . '/novasoft/' . $this->configuracion->getScraper()->getNovasoft()->getConexion()
            . ( $url ? '/' . $url : '');
    }
}