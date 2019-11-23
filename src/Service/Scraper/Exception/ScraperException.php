<?php


namespace App\Service\Scraper\Exception;



use App\Service\Scraper\Response\ResponseManager;

class ScraperException extends \Exception
{

    /**
     * @param $message
     * @param $code
     * @return ScraperConflictException|ScraperException|ScraperNotFoundException|ScraperTimeoutException
     */
    public static function create($message, $code)
    {
        switch($code) {
            case ResponseManager::NOTFOUND:
                $exception = new ScraperNotFoundException($message);
                break;
            case ResponseManager::CONFLICT:
                $exception = new ScraperConflictException($message);
                break;
            case ResponseManager::TIMEOUT:
                $exception = new ScraperTimeoutException($message);
                break;
            default:
                $exception = new ScraperException($message);
        }
        return $exception;
    }
}