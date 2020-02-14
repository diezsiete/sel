<?php


namespace App\Message\Novasoft\Api;


class InsertHvChildInNovasoft
{
    private $childId;
    /**
     * @var string
     */
    private $childClass;
    /**
     * @var int
     */
    private $hvId;

    public function __construct($childId, string $childClass, int $hvId)
    {
        $this->childId = $childId;
        $this->childClass = $childClass;
        $this->hvId = $hvId;
    }

    /**
     * @return mixed
     */
    public function getChildId()
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

    /**
     * @return int
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }
}