<?php


namespace App\Service\Novasoft\Api;


use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NovasoftApiClient
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
     * @var string
     */
    private $db;

    public function __construct(HttpClientInterface $httpClient, string $url, string $db)
    {
        $this->httpClient = $httpClient;
        $this->url = $url . '/api';
        $this->db = $db;
    }
    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    public function empleado(string $id) {
        try {
            $response = $this->httpClient->request('GET', $this->url . "/empleado/$id");
            return $response->toArray();
        } catch (ClientException $e) {
            if($e->getCode() === 404) {
                return null;
            }
            throw $e;
        }
    }



}