<?php

namespace App\Service;


use HttpException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HttpClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get(string $url, array $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    public function postJson(string $url, $jsonData, $options = [])
    {
        return $this->request('POST', $url, ['json' => $jsonData] + $options);
    }

    public function deleteJson(string $url, $data = [])
    {
        $options = $data ? ['json' => $data] : [];
        return $this->request('DELETE', $url, $options);
    }

    public function request(string $method, string $url, array $options = [])
    {
        return $this->httpClient->request($method, $url, $options);
    }

    /**
     * @param string $url
     * @return false|resource
     * @throws TransportExceptionInterface
     */
    public function download(string $url)
    {
        $response = $this->request('GET', $url, ['buffer' => false]);
        if (200 !== $response->getStatusCode()) {
            if($response->getStatusCode() >= 500) {
                throw new ServerException($response);
            }
            throw new ClientException($response);
        }
        $tmpStream = tmpfile();
        foreach ($this->httpClient->stream($response) as $chunk) {
            fwrite($tmpStream, $chunk->getContent());
        }
        return $tmpStream;
    }
}