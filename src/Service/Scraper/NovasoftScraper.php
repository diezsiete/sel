<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperTimeoutException;
use Exception;

class NovasoftScraper
{
    private $defaultTimeout = 60;

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

    public function putHv($data, $timeout = null)
    {
        $timeout = $timeout ?? $this->defaultTimeout;
        return $this->scraperClient->putStream($this->getFullUrl('/hv/datos-basicos'), $data, ['timeout' => $timeout]);
    }

    public function postHv($data, $timeout = null)
    {
        $timeout = $timeout ?? $this->defaultTimeout;
        return $this->scraperClient->postStream($this->getFullUrl('/hv'), $data, ['timeout' => $timeout]);
    }

    /**
     * @param $data
     * @param null|int $timeout
     * @return Response\ScraperResponse|mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function insertChild($data, $timeout = null)
    {
        $timeout = $timeout ?? $this->defaultTimeout;
        return $this->scraperClient->postStream($this->getFullUrl('/hv/child'), $data, ['timeout' => $timeout]);
    }

    /**
     * @param $data
     * @param null|int $timeout
     * @return Response\ScraperResponse|mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function updateChilds($data, $timeout = null)
    {
        $timeout = $timeout ?? $this->defaultTimeout;
        return $this->scraperClient->putStream($this->getFullUrl('/hv/childs'), $data, ['timeout' => $timeout]);
    }

    /**
     * @param string $url
     * @param bool $hvPrefix
     * @return string
     */
    protected function getFullUrl(string $url = '') {
        return '/' . $this->configuracion->getScraper()->getNovasoft()->getBrowser()
            . '/novasoft/' . $this->configuracion->getScraper()->getNovasoft()->getConexion()
            . ( $url ? $url : '');
    }
}