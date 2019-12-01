<?php


namespace App\Message\Scraper;


class UpdateHvInNovasoftSuccess
{
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
}