<?php


namespace Sel\RemoteBundle\Helper\SelClient;


use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    private $httpClientResponse;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $env;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(
        HttpClientInterface $httpClient,
        ResponseInterface $httpClientResponse,
        LoggerInterface $logger,
        string $env
    ) {
        $this->httpClient = $httpClient;
        $this->httpClientResponse = $httpClientResponse;
        $this->logger = $logger;
        $this->env = $env;
    }

    public function getStatusCode(): int
    {
        return $this->httpClientResponse->getStatusCode();
    }

    public function toArray(...$allowedCodes): ?array
    {
        if (!$allowedCodes) {
            $allowedCodes = [400, 404];
        }
        try {
            $throw = true;
            if (in_array(400, $allowedCodes) && $this->getStatusCode() === 400) {
                $throw = false;
            }
            return $this->httpClientResponse->toArray($throw);
        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            $this->handleException($e, $allowedCodes);
        }
        return null;
    }

    public function stream()
    {
        if (200 !== $this->httpClientResponse->getStatusCode()) {
            throw $this->handleException(new \Exception('...'));
        }
        return $this->httpClient->stream($this->httpClientResponse);
    }

    public function getContent($throw = true)
    {
        return $this->httpClientResponse->getContent($throw);
    }

    private function handleException(\Throwable $e, $allowedCodes = [])
    {
        if (!$e instanceof ClientExceptionInterface || !in_array(404, $allowedCodes) || $e->getCode() !== 404) {
            if ($this->env === 'dev') {
                throw $e;
            } else {
                $this->logger->error($e);
            }
        }
        return $e;
    }
}