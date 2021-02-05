<?php

namespace Sel\RemoteBundle\Service;

use Psr\Log\LoggerInterface;
use Sel\RemoteBundle\Helper\SelClient\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SelClient
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $env;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, string $env)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->env = $env;
    }

    public function request(?string $url = null)
    {
        return new Request($this->httpClient, $this->logger, $this->env, $url);
    }

    public function get(?string $url = null)
    {
        return (new Request($this->httpClient, $this->logger, $this->env, $url))->get();
    }

    public function stream($responses, float $timeout = null)
    {
        return $this->httpClient->stream($responses, $timeout);
    }
}