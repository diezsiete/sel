<?php


namespace App\Message\Novasoft\Api;


class InsertHvInNovasoft
{
    private $hvId;

    public function __construct($hvId)
    {
        $this->hvId = $hvId;
    }

    /**
     * @return mixed
     */
    public function getHvId()
    {
        return $this->hvId;
    }
}