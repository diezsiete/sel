<?php


namespace App\Service\Scraper\Response;


class ScraperResponse
{
    private $ok = false;
    private $message = "";
    private $code = 200;

    /**
     * ScrapperResponse constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->ok = $data['ok'];
        $this->message = $data['message'];
        $this->code = $data['code'];
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