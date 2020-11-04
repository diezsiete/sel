<?php


namespace App\Service\HttpClient;


use App\Service\HttpClient\Request\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

    public function request(?string $url = null)
    {
        return new Request($this->httpClient, $url);
    }

    public function get(?string $url = null)
    {
        return (new Request($this->httpClient, $url))->get();
    }
}