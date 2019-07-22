<?php


namespace App\Service\Scrapper;


class ScrapperException extends \Exception
{
    /**
     * @var ScrapperResponse
     */
    private $response;

    public static function create(ScrapperResponse $scrapperResponse)
    {
        $exception = new static($scrapperResponse->getMessage());
        $exception->response = $scrapperResponse;
        return $exception;
    }

    /**
     * @return ScrapperResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}