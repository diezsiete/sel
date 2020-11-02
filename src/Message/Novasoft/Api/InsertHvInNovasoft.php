<?php


namespace App\Message\Novasoft\Api;


class InsertHvInNovasoft implements NapiHvMessage
{
    use MessageWithSolicitud;

    /**
     * @var int
     */
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