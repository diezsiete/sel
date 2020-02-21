<?php


namespace App\Message\Novasoft\Api;


trait MessageWithScraperSolicitud
{
    /**
     * @var int
     */
    private $solicitudId;

    /**
     * @return int
     */
    public function getSolicitudId(): int
    {
        return $this->solicitudId;
    }

    /**
     * @param int $solicitudId
     */
    public function setSolicitudId(int $solicitudId)
    {
        $this->solicitudId = $solicitudId;
    }
}