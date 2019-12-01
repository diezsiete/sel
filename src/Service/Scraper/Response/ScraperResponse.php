<?php


namespace App\Service\Scraper\Response;


class ScraperResponse
{
    private $ok = false;
    private $message = "";
    private $code = 200;
    private $log = "";

    /**
     * ScrapperResponse constructor.
     * @param $data
     */
    public function __construct(?array $data = null)
    {
        if($data) {
            $this->ok = $data['ok'];
            $this->message = $data['message'];
            $this->code = $data['code'];
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

    /**
     * @param bool|mixed $ok
     * @return ScraperResponse
     */
    public function setOk($ok)
    {
        $this->ok = $ok;
        return $this;
    }

    /**
     * @param mixed|string $message
     * @return ScraperResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param int|mixed $code
     * @return ScraperResponse
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param $log
     * @return ScraperResponse
     */
    public function setLog($log): ScraperResponse
    {
        $this->log = $log;
        return $this;
    }
}