<?php


namespace App\Message\Scraper;

class UpdateHvInNovasoft
{
    const ACTION_UPDATE = "UPDATE";
    const ACTION_INSERT = "INSERT";
    const ACTION_CHILD_INSERT = "CHILD_INSERT";
    const ACTION_CHILD_UPDATE = "CHILD_UPDATE";
    const ACTION_CHILD_DELETE = "CHILD_DELETE";

    /**
     * @var int
     */
    protected $hvId;
    /**
     * @var array
     */
    protected $hvData;

    /**
     * @var string
     */
    private $action;

    /**
     * UpdateHvInNovasoft constructor.
     * @param int $id
     * @param array $hvData
     * @param string $action
     */
    public function __construct($id, $hvData, $action)
    {
        $this->hvId = $id;
        $this->hvData = $hvData;
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getHvData()
    {
        return $this->hvData;
    }

    /**
     * @return int
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return bool
     */
    public function isUpdate()
    {
        return $this->action === self::ACTION_UPDATE;
    }

    /**
     * @return bool
     */
    public function isInsert()
    {
        return $this->action === self::ACTION_INSERT;
    }

    /**
     * @return bool
     */
    public function isChildInsert()
    {
        return $this->action === self::ACTION_CHILD_INSERT;
    }

    /**
     * @return bool
     */
    public function isChildUpdate()
    {
        return $this->action === self::ACTION_CHILD_UPDATE;
    }

    /**
     * @return bool
     */
    public function isChildDelete()
    {
        return $this->action === self::ACTION_CHILD_DELETE;
    }
}