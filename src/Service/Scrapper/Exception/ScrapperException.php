<?php


namespace App\Service\Scrapper\Exception;



use App\Service\Scrapper\Response\ResponseManager;

class ScrapperException extends \Exception
{

    public static function create($message, $code)
    {
        switch($code) {
            case ResponseManager::NOTFOUND:
                $exception = new ScrapperNotFoundException($message);
                break;
            case ResponseManager::CONFLICT:
                $exception = new ScrapperConflictException($message);
                break;
            default:
                $exception = new ScrapperException($message);
        }
        return $exception;
    }
}