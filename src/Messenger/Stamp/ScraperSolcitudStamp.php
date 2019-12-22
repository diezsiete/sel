<?php


namespace App\Messenger\Stamp;


use Symfony\Component\Messenger\Stamp\StampInterface;

class ScraperSolcitudStamp implements StampInterface
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
}