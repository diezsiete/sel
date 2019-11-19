<?php


namespace App\Message;


use App\Entity\Hv;

class UploadToNovasoft
{
    const CHILD_METHOD_INSERT = "INSERT";
    const CHILD_METHOD_UPDATE = "UPDATE";
    const CHILD_METHOD_DELETE = "DELETE";

    /**
     * @var Hv
     */
    private $hvId;
    private $childId;
    private $childClass;
    private $childMethod;

    public function __construct(int $hvId, ?int $childId = null, ?string $childClass = null, ?string $childMethod = null)
    {
        $this->hvId = $hvId;
        $this->childId = $childId;
        $this->childClass = $childClass;
        $this->childMethod = $childMethod;
    }

    /**
     * @return int
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }

    /**
     * @return int|null
     */
    public function getChildId(): ?int
    {
        return $this->childId;
    }

    /**
     * @return string|null
     */
    public function getChildClass(): ?string
    {
        return $this->childClass;
    }

    /**
     * @return string|null
     */
    public function getChildMethod(): ?string
    {
        return $this->childMethod;
    }

}