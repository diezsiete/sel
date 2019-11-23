<?php


namespace App\Message;


class UploadToNovasoftSuccess
{
    /**
     * @var int
     */
    private $hvId;
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
            $this->action = $action ?? UploadToNovasoft::ACTION_UPDATE;
        }
    }

    /**
     * @return int
     */
    public function getHvId()
    {
        return $this->hvId;
    }

    /**
     * @return null
     */
    public function getChildId()
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