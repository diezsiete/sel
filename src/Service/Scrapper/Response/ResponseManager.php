<?php


namespace App\Service\Scrapper\Response;


use App\Service\Scrapper\Exception\ScrapperConflictException;
use App\Service\Scrapper\Exception\ScrapperException;
use App\Service\Scrapper\Exception\ScrapperNotFoundException;

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
     * @throws ScrapperException
     * @throws ScrapperConflictException
     * @throws ScrapperNotFoundException
     */
    public static function instance($data)
    {
        if(isset($data['ok']) && !$data['ok']) {
            throw ScrapperException::create($data['message'], $data['code']);
        }
        $class = __NAMESPACE__ . '\\' . $data['class'];

        return new $class($data);
    }
}