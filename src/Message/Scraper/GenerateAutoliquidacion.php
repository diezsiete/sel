<?php

namespace App\Message\Scraper;

class GenerateAutoliquidacion
{
    /**
     * @var int
     */
    private $autoliquidacionEmpleadoId;
    /**
     * @var int
     */
    private $page;

    public function __construct(int $autoliquidacionEmpleadoId, int $page = 1)
    {
        $this->autoliquidacionEmpleadoId = $autoliquidacionEmpleadoId;
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getAutoliquidacionEmpleadoId(): int
    {
        return $this->autoliquidacionEmpleadoId;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}