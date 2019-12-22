<?php


namespace App\Message\Scraper;


class InsertHvInNovasoft implements MessageWithSolicitud
{
    /**
     * @var int
     */
    private $solicitudId;

    public function __construct(int $solicitudId)
    {
        $this->solicitudId = $solicitudId;
    }

    /**
     * @return int
     */
    public function getSolicitudId(): int
    {
        return $this->solicitudId;
    }


    public function setSolicitudId(int $solicitudId)
    {
        $this->solicitudId = $solicitudId;
    }
}