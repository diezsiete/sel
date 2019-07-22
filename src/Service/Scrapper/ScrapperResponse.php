<?php


namespace App\Service\Scrapper;


class ScrapperResponse
{
    private $ok = false;
    private $message = "";

    public function __construct($data)
    {
        $this->ok = $data['ok'];
        $this->message = $data['message'];

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


}