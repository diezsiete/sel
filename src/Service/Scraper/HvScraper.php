<?php


namespace App\Service\Scraper;

use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperTimeoutException;
use App\Service\Scraper\Response\ScraperResponse;

/**
 * Class HvScrapper
 * @package App\Service\Scraper
 */
class HvScraper
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
     * @param array $data
     * @return ScraperResponse
     * @throws ScraperException
     * @throws ScraperTimeoutException
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperNotFoundException
     */
    public function putHv($data)
    {
        return $this->scraperClient->put($this->getFullUrl('datos-basicos'), $data, ['timeout' => 120]);
    }

    /**
     * @param array $data
     * @return ScraperResponse
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function postHv($data)
    {
        return $this->scraperClient->post($this->getFullUrl(), $data, ['timeout' => 240]);
    }

    /**
     * @param array $data
     * @return ScraperResponse
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function insertChild($data)
    {
        return $this->scraperClient->post($this->getFullUrl('child'), $data, ['timeout' => 80]);
    }

    /**
     * @param array $data
     * @return ScraperResponse
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function updateChild($data)
    {
        return $this->scraperClient->put($this->getFullUrl('child'), $data, ['timeout' => 60]);
    }

    /**
     * @param $data
     * @return ScraperResponse
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function deleteChild($data)
    {
        return $this->scraperClient->put($this->getFullUrl('child'), $data, ['timeout' => 80]);
    }

    /**
     * @param string $url
     * @param bool $hvPrefix
     * @return string
     */
    private function getFullUrl(string $url = '', bool $hvPrefix = true) {
        return '/' . $this->configuracion->getScraper()->getNovasoft()->getBrowser()
            . '/novasoft/' . $this->configuracion->getScraper()->getNovasoft()->getConexion()
            . ($hvPrefix ? '/hv' : '') . ( $url ? '/' . $url : '');
    }
}
