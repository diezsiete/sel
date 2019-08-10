<?php


namespace App\Service\Scraper\Exception;



use App\Service\Scraper\Response\ResponseManager;

class ScraperException extends \Exception
{

    public static function create($message, $code)
    {
        switch($code) {
            case ResponseManager::NOTFOUND:
                $exception = new ScraperNotFoundException($message);
                break;
            case ResponseManager::CONFLICT:
                $exception = new ScraperConflictException($message);
                break;
            default:
                $exception = new ScraperException($message);
        }
        return $exception;
    }
}