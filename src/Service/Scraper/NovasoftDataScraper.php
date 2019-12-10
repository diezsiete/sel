<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Response\ScraperResponse;

class NovasoftDataScraper
{
    /**
     * @var ScraperClient
     */
    private $scraperClient;

    /**
     * @var Configuracion
     */
    private $configuracion;


    public function __construct(Configuracion $configuracion, ScraperClient $scraperClient)
    {
        $this->scraperClient = $scraperClient;
        $this->configuracion = $configuracion;
    }

    /**
     * @param $ident
     * @return ScraperResponse
     * @throws Exception\ScraperClientException
     * @throws Exception\ScraperConflictException
     * @throws Exception\ScraperException
     * @throws Exception\ScraperNotFoundException
     * @throws Exception\ScraperTimeoutException
     */
    public function findConvenioForEmpleado($ident)
    {
        $browser = $this->configuracion->getScraper()->getNovasoft()->getBrowser();
        $conexion = $this->configuracion->getScraper()->getNovasoft()->getConexion();
        return $this->scraperClient->get("/$browser/novasoft/$conexion/convenio/$ident");
    }


    /**
     * @return ScraperResponse
     * @throws Exception\ScraperClientException
     * @throws Exception\ScraperConflictException
     * @throws Exception\ScraperException
     * @throws Exception\ScraperNotFoundException
     * @throws Exception\ScraperTimeoutException
     */
    public function getInstitutos()
    {
        $browser = $this->configuracion->getScraper()->getNovasoft()->getBrowser();
        $conexion = $this->configuracion->getScraper()->getNovasoft()->getConexion();
        return $this->scraperClient->get("/$browser/novasoft/$conexion/data/instituto", ['timeout' => 240]);
    }

}