<?php


namespace App\Service\Scraper\Exception;


use Exception;

class ScraperClientException extends Exception
{
    protected $clientException;

    public static function instance(Exception $clientException): ScraperClientException
    {
        $exception = new self($clientException->getMessage());
        $exception->clientException = $clientException;
        return $exception;
    }

    /**
     * @return mixed
     */
    public function getClientException()
    {
        return $this->clientException;
    }
}