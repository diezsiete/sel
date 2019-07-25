<?php


namespace App\Service\Scrapper;


use App\Service\Scrapper\Exception\ScrapperException;
use App\Service\Scrapper\Exception\ScrapperNotFoundException;

class ScrapperResponse
{
    const OK = 200;
    const NOTFOUND = 404;
    const TIMEOUT = 408;
    const CONFLICT = 409;
    const ERROR = 500;

    private $ok = false;
    private $message = "";
    private $code = 200;

    /**
     * ScrapperResponse constructor.
     * @param $data
     * @throws ScrapperNotFoundException
     * @throws ScrapperException
     */
    public function __construct($data)
    {
        $this->ok = $data['ok'];
        $this->message = $data['message'];
        $this->code = $data['code'];

        if(!$this->ok) {
            throw ScrapperException::create($this);
        }
    }

    /**
     * @return mixed
     */
    public function isOk()
    {
        return $this->ok;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

}