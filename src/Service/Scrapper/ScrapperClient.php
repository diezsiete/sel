<?php


namespace App\Service\Scrapper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scrapper\Exception\ScrapperException;
use App\Service\Scrapper\Exception\ScrapperNotFoundException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScrapperClient
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
        return $this->httpClient->request($method, $this->getFullUrl($url), $options);
    }

    /**
     * @param string $url
     * @param array $options
     * @return ScrapperResponse
     * @throws ScrapperException
     * @throws ScrapperNotFoundException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function get(string $url, array $options = [])
    {
        $response = $this->request('GET', $url, $options);
        return new ScrapperResponse($response->toArray());
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