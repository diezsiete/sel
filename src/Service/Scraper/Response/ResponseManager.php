<?php


namespace App\Service\Scraper\Response;


use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Exception\ScraperTimeoutException;
use Exception;
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


    public function __construct(PropertyAccessorInterface $propertyAccessor, EventDispatcherInterface $eventDispatcher)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->eventDispatcher = $eventDispatcher;
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

    public function handleStreamResponse(ResponseInterface $response, HttpClientInterface $httpClient, string $responseClass = null)
    {
        try {
            if (200 !== $response->getStatusCode()) {
                $message = $responseBody['message'] ?? "Error " . $response->getStatusCode();
                throw ScraperException::create($message, $responseBody['code'] ??  static::ERROR, $responseBody['log'] ?? '');
            }

            $responses = [];
            $count = 0;
            foreach ($httpClient->stream($response) as $chunk) {
                $responses[$count] = $chunk->getContent();
                $this->eventDispatcher->dispatch(new ScraperStreamResponseEvent($responses[$count]));
                $count = $count === 0 ? 1 : 0;
            }
            $responseBody = isset($responses[0]) ? trim($responses[0]) : "";
            if(isset($responses[1]) && trim($responses[1])) {
                $responseBody = trim($responses[1]);
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
        $response = !is_object($responseClass) ? new $responseClass() : $responseClass;
        foreach($responseBody as $key => $value) {
            if($this->propertyAccessor->isWritable($response, $key)) {
                $this->propertyAccessor->setValue($response, $key, $value);
            }
        }
        return $response;
    }
}