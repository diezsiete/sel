<?php


namespace App\Message\Scraper;


class DownloadAutoliquidacion
{
    /**
     * @var int
     */
    private $autoliquidacionEmpleadoId;

    public function __construct(int $autoliquidacionEmpleadoId)
    {
        $this->autoliquidacionEmpleadoId = $autoliquidacionEmpleadoId;
    }

    /**
     * @return int
     */
    public function getAutoliquidacionEmpleadoId(): int
    {
        return $this->autoliquidacionEmpleadoId;
    }
}