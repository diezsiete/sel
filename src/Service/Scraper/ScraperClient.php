<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Response\ResponseManager;
use App\Service\Scraper\Response\ScraperResponse;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ScraperClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(HttpClientInterface $httpClient, Configuracion $configuracion)
    {

        $this->httpClient = $httpClient;
        $this->configuracion = $configuracion;
    }

    public function request(string $method, string $url, array $options = [])
    {
        $response =  $this->httpClient->request($method, $this->getFullUrl($url), $options);
        return $response;
    }

    /**
     * @param string $url
     * @param array $options
     * @return ScraperResponse
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function get(string $url, array $options = [])
    {
        $response = $this->request('GET', $url, $options);
        return ResponseManager::instance($response->toArray());
    }

    public function post(string $url, $jsonData)
    {
        $response = $this->request('POST', $url, [
            'json' => $jsonData
        ]);
        return ResponseManager::catchError($response);
        // return ResponseManager::instance($response->toArray());
    }

    /**
     * @param string $url
     * @param $data
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ScraperConflictException
     * @throws RedirectionExceptionInterface
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function put(string $url, $data)
    {
        $response = $this->request('PUT', $url, ['json' => $data]);
        return ResponseManager::catchError($response);
    }

    /**
     * @param string $url
     * @return resource
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function download(string $url)
    {
        $response = $this->request('GET', $url, ['buffer' => false]);

        if (200 !== $response->getStatusCode()) {
            throw new Exception('Download failed. Response code : ' . $response->getStatusCode());
        }

        $tmpStream = tmpfile();
        foreach($this->httpClient->stream($response) as $chunk) {
            fwrite($tmpStream, $chunk->getContent());
        }
        return $tmpStream;
    }

    private function getFullUrl($url)
    {
        return $this->configuracion->getScrapper()->url . $url;
    }
}