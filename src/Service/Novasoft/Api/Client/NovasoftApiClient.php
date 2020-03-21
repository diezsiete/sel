<?php


namespace App\Service\Novasoft\Api\Client;

use App\Exception\Novasoft\Api\NotFoundException;
use App\Service\ExceptionHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


abstract class NovasoftApiClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $url;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;
    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;
    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandler;
    /**
     * @var string
     */
    private $napiDb;
    /**
     * @var string
     */
    private $napiDbTmp;

    public function __construct(HttpClientInterface $httpClient, string $napiUrl, string $napiDb,
                                NormalizerInterface $normalizer, DenormalizerInterface $denormalizer,
                                ExceptionHandler $exceptionHandler)
    {
        $this->httpClient = $httpClient;
        $this->url = $napiUrl;
        $this->normalizer = $normalizer;
        $this->denormalizer = $denormalizer;
        $this->exceptionHandler = $exceptionHandler;
        $this->napiDb = $napiDb;
    }

    public function getDb()
    {
        return $this->napiDbTmp ?: $this->napiDb;
    }

    /**
     * @param $url
     * @return mixed|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function sendGet($url)
    {
        try {
            return $this->request('GET', $url, static function (ResponseInterface $response) {
                return $response->toArray();
            });
        } catch (NotFoundException $e) {
            return null;
        }
    }

    protected function sendPost(string $url, $data, $options = [])
    {
        $url = $this->buildUrl($url);
        $response = null;
        try {
            $response = $this->httpClient->request('POST', $url, ['json' => $data] + $options);
            return $response->toArray();
        }
        catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * @param string $url
     * @param $data
     * @param array $options
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws NotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function sendPut(string $url , $data, $options = [])
    {
        return $this->request('PUT', $url, ['json' => $data] + $options, static function (ResponseInterface $response) {
            return $response->toArray();
        });
    }


    /**
     * @param string $url
     * @param array $options
     * @return int 204 is ok
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws NotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function sendDelete(string $url, $options = []): int
    {
        return $this->request('DELETE', $url, $options, static function (ResponseInterface $response) {
            $statusCode = $response->getStatusCode();
            if($statusCode >= 400) {
                return $response->getContent();
            }
            return $statusCode;
        });
    }

    /**
     * @param $method
     * @param $url
     * @param array|callable $responseHandler
     * @param array|callable $options
     * @return mixed
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws NotFoundException
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function request($method, $url, $responseHandler, $options = [])
    {
        if(is_array($responseHandler)) {
            $responseHandleTmp = $options;
            $options = $responseHandler;
            $responseHandler = $responseHandleTmp;
        }
        try {
            $response = $this->httpClient->request($method, $this->buildUrl($url), $options);
            return $responseHandler($response);
        } catch (ClientException $e) {
            if($e->getCode() === 404) {
                $e = new NotFoundException($e);
            }
            throw $e;
        }
    }

    protected function buildUrl($url): string
    {
        $db = $this->getDb();
        $base = "/{$db}/api";

        //urls de hydra collection ya vienen con $base, eliminamos
        $url = str_replace($base, '', $url);

        return "{$this->url}/{$db}/api{$url}";
    }

    protected function db(?string $db = null)
    {
        $this->napiDbTmp = $db;
        return $this;
    }
}