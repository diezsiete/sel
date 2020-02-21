<?php


namespace App\Message\Novasoft\Api;


class UpdateHvInNovasoft
{
    use MessageWithScraperSolicitud;

    private $hvId;

    public function __construct($hvId)
    {
        $this->hvId = $hvId;
    }

    /**
     * @return integer
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }
}