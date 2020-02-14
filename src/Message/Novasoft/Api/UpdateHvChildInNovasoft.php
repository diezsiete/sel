<?php


namespace App\Message\Novasoft\Api;


class UpdateHvChildInNovasoft
{
    private $childId;
    /**
     * @var string
     */
    private $childClass;

    public function __construct($childId, string $childClass)
    {
        $this->childId = $childId;
        $this->childClass = $childClass;
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
}