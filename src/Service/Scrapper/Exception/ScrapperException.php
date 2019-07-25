<?php


namespace App\Service\Scrapper\Exception;

use App\Service\Scrapper\ScrapperResponse;

class ScrapperException extends \Exception
{
    /**
     * @var ScrapperResponse
     */
    private $response;

    public static function create(ScrapperResponse $scrapperResponse)
    {
        $code = $scrapperResponse->getCode();
        switch($code) {
            case ScrapperResponse::NOTFOUND:
                $exception = new ScrapperNotFoundException($scrapperResponse->getMessage());
                break;
            case ScrapperResponse::CONFLICT:
                $exception = new ScrapperConflictException($scrapperResponse->getMessage());
                break;
            default:
                $exception = new ScrapperException($scrapperResponse->getMessage());
        }
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