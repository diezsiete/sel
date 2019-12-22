<?php


namespace App\Message\Scraper;


class UpdateHvChildsInNovasoft implements MessageWithSolicitud
{
    /**
     * @var int
     */
    private $solicitudId;

    /**
     * @var string
     */
    private $childClass;

    public function __construct(string $childClass, ?int $solicitudId = null)
    {
        $this->childClass = $childClass;
        if($solicitudId) {
            $this->solicitudId = $solicitudId;
        }
    }

    /**
     * @return int
     */
    public function getSolicitudId(): int
    {
        return $this->solicitudId;
    }

    /**
     * @param int $solicitudId
     * @return UpdateHvChildsInNovasoft
     */
    public function setSolicitudId(int $solicitudId): UpdateHvChildsInNovasoft
    {
        $this->solicitudId = $solicitudId;
        return $this;
    }


    /**
     * @return string
     */
    public function getChildClass(): string
    {
        return $this->childClass;
    }


}