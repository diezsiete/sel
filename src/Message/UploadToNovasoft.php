<?php


namespace App\Message;

class UploadToNovasoft
{
    const ACTION_UPDATE = "UPDATE";
    const ACTION_INSERT = "INSERT";
    const ACTION_CHILD_INSERT = "CHILD_INSERT";
    const ACTION_CHILD_UPDATE = "CHILD_UPDATE";
    const ACTION_CHILD_DELETE = "CHILD_DELETE";

    /**
     * @var int
     *
     */
    private $hvId;

    /**
     * @var null
     */
    private $childId;
    private $childClass;
    private $action;

    public function __construct(int $hvId, $childId = null, ?string $childClass = null, ?string $action = null)
    {
        $this->hvId = $hvId;
        if(is_string($childId)) {
            $this->action = $childId;
        } else {
            $this->childId = $childId;
            $this->childClass = $childClass;
            $this->action = $action ?? static::ACTION_UPDATE;
        }
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
    public function getAction(): ?string
    {
        return $this->action;
    }

}