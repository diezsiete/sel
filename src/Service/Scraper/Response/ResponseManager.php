<?php


namespace App\Service\Scraper\Response;


use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperTimeoutException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseManager
{
    const OK = 200;
    const NOTFOUND = 404;
    const TIMEOUT = 408;
    const CONFLICT = 409;
    const ERROR = 500;
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(PropertyAccessorInterface $propertyAccessor, EventDispatcherInterface $eventDispatcher, LoggerInterface $messengerAuditLogger)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $messengerAuditLogger;
    }

    /**
     * @deprecated
     */
    public static function instance($data)
    {
        if(isset($data['ok']) && !$data['ok']) {
            throw ScraperException::create($data['message'], $data['code']);
        }
        $class = __NAMESPACE__ . '\\' . $data['class'];

        return new $class($data);
    }

    /**
     * @param ResponseInterface $response
     * @param null $responseClass
     * @return mixed
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function handleResponse($response, $responseClass = null)
    {
        try {
            $responseBody = $response->toArray(false);
            if($response->getStatusCode() !== 200) {
                $message = $responseBody['message'] ?? "Error " . $response->getStatusCode();
                throw ScraperException::create($message, $responseBody['code'] ??  static::ERROR, $responseBody['log'] ?? '');
            }
            if($responseClass) {
                $responseBody = $this->convertResponseBodyToObject($responseBody, $responseClass);
            }
            return $responseBody;
        } catch (DecodingExceptionInterface | TransportExceptionInterface | RedirectionExceptionInterface |
                 ClientExceptionInterface | ServerExceptionInterface $e) {
            throw ScraperClientException::instance($e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @param HttpClientInterface $httpClient
     * @param float $timeout
     * @param string|null $responseClass
     * @return mixed|ScraperResponse
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ScraperTimeoutException
     */
    public function handleStreamResponse(ResponseInterface $response, HttpClientInterface $httpClient, float $timeout = 2, string $responseClass = null)
    {
        try {
            if (200 !== $response->getStatusCode()) {
                $message = $responseBody['message'] ?? "Error " . $response->getStatusCode();
                throw ScraperException::create($message, $responseBody['code'] ??  static::ERROR, $responseBody['log'] ?? '');
            }

            $responseBody = "";
            foreach ($httpClient->stream($response, $timeout) as $chunk) {
                if($chunk->isTimeout()) {
                    $this->logger->warning("stream timeout for '$timeout' seconds");
                    throw new ScraperTimeoutException("stream timeout for '$timeout' seconds");
                }

                if(!$chunk->isLast()) {
                    $responseBody = $chunk->getContent();
                    $this->logger->debug($responseBody);
                    $this->eventDispatcher->dispatch(new ScraperStreamResponseEvent($responseBody));
                }
            }

            if($responseClass && $responseBody) {
                $responseBody = $this->convertResponseBodyToObject(json_decode($responseBody, true), $responseClass);
            }
            return $responseBody;

        } catch (TransportExceptionInterface  $e) {
            throw ScraperClientException::instance($e);
        }
    }

    private function convertResponseBodyToObject($responseBody, $responseClass)
    {
        if(isset($responseBody['code']) && $responseBody['code'] !== 200) {
            $message = $responseBody['message'] ?? "Error " . $responseBody['code'];
            throw ScraperException::create($message, $responseBody['code'], $responseBody['log'] ?? '');
        }
        $response = !is_object($responseClass) ? new $responseClass() : $responseClass;
        foreach($responseBody as $key => $value) {
            if($this->propertyAccessor->isWritable($response, $key)) {
                $this->propertyAccessor->setValue($response, $key, $value);
            }
        }
        return $response;
    }
}