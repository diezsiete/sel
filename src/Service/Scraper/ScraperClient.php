<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Exception\ScraperClientException;
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
    /**
     * @var ResponseManager
     */
    private $responseManager;

    /**
     * @var int|float
     */
    private $timeout = 60;

    public function __construct(HttpClientInterface $httpClient, Configuracion $configuracion, ResponseManager $responseManager)
    {

        $this->httpClient = $httpClient;
        $this->configuracion = $configuracion;
        $this->responseManager = $responseManager;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws ScraperClientException
     */
    public function request(string $method, string $url, array $options = [])
    {
        try {
            $response = $this->httpClient->request($method, $this->getFullUrl($url), $options);
        } catch (TransportExceptionInterface $e) {
            throw ScraperClientException::instance($e);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param string $responseClass
     * @param array $options
     * @return mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function get(string $url, $responseClass = ScraperResponse::class, array $options = [])
    {
        $response = $this->request('GET', $url, $options);
        return $this->responseManager->handleResponse($response, $responseClass);
    }

    /**
     * @param string $url
     * @param $jsonData
     * @param string $responseClass
     * @return mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function post(string $url, $jsonData, $options = [])
    {
        $options = ['json' => $jsonData] + $options;
        $response = $this->request('POST', $url, $options);
        return $this->responseManager->handleResponse($response, ScraperResponse::class);
    }

    /**
     * @param string $url
     * @param $data
     * @return mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function put(string $url, $data)
    {
        $response = $this->request('PUT', $url, ['json' => $data]);
        return $this->responseManager->handleResponse($response);
    }

    /**
     * @param string $url
     * @param array $data
     * @param string $responseClass
     * @return mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function delete(string $url, $data = [], $responseClass = ScraperResponse::class)
    {
        $options = $data ? ['json' => $data] : [];
        $response = $this->request('DELETE', $url, $options);
        return $this->responseManager->handleResponse($response, $responseClass);
    }

    /**
     * @param string $url
     * @return bool|resource
     * @throws ScraperClientException
     */
    public function download(string $url)
    {

        try {
            $response = $this->request('GET', $url, ['buffer' => false]);
            if (200 !== $response->getStatusCode()) {
                throw new Exception('Download failed. Response code : ' . $response->getStatusCode());
            }
            $tmpStream = tmpfile();
            foreach ($this->httpClient->stream($response) as $chunk) {
                fwrite($tmpStream, $chunk->getContent());
            }
            return $tmpStream;
        } catch (ScraperClientException $e) {
            throw $e;
        } catch (TransportExceptionInterface $e) {
            throw ScraperClientException::instance($e);
        }
    }

    private function getFullUrl($url)
    {
        return $this->configuracion->getScraper()->url . $url;
    }
}