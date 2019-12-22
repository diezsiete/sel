<?php


namespace App\Message\Scraper;


class InsertHvChildInNovasoft implements MessageWithSolicitud
{
    /**
     * @var int
     */
    private $solicitudId;
    /**
     * @var int
     */
    private $childId;
    /**
     * @var string
     */
    private $childClass;

    public function __construct(int $childId, string $childClass, ?int $solicitudId = null)
    {
        $this->childId = $childId;
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
     * @return InsertHvChildInNovasoft
     */
    public function setSolicitudId(int $solicitudId): InsertHvChildInNovasoft
    {
        $this->solicitudId = $solicitudId;
        return $this;
    }


    /**
     * @return int
     */
    public function getChildId(): int
    {
        return $this->childId;
    }

    /**
     * @return string
     */
    public function getChildClass(): string
    {
        return $this->childClass;
    }


}