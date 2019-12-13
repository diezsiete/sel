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
    /**
     * @var int
     */
    private $progresoId;

    public function __construct(int $autoliquidacionEmpleadoId, int $progresoId, int $page = 1)
    {
        $this->autoliquidacionEmpleadoId = $autoliquidacionEmpleadoId;
        $this->page = $page;
        $this->progresoId = $progresoId;
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
    public function getProgresoId(): int
    {
        return $this->progresoId;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}