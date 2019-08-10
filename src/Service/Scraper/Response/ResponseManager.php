<?php


namespace App\Service\Scraper\Response;


use App\Service\Scraper\Exception\ScraperConflictException;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ResponseManager
{
    const OK = 200;
    const NOTFOUND = 404;
    const TIMEOUT = 408;
    const CONFLICT = 409;
    const ERROR = 500;

    /**
     * @param $data
     * @return mixed
     * @throws ScraperException
     * @throws ScraperConflictException
     * @throws ScraperNotFoundException
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
     * @return array
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function catchError($response)
    {
        $responseArray = $response->toArray(false);
        if($response->getStatusCode() !== 200) {
            $message = $responseArray['message'] ?? "Error " . $response->getStatusCode();
            throw ScraperException::create($message, $data['code'] ??  static::ERROR);
        }
        return $responseArray;
    }
}