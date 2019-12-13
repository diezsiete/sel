<?php


namespace App\Message\Scraper;


class DownloadAutoliquidacion
{
    /**
     * @var int
     */
    private $autoliquidacionEmpleadoId;
    /**
     * @var int
     */
    private $autoliquidacionProgresoId;

    public function __construct(int $autoliquidacionEmpleadoId, int $autoliquidacionProgresoId)
    {
        $this->autoliquidacionEmpleadoId = $autoliquidacionEmpleadoId;
        $this->autoliquidacionProgresoId = $autoliquidacionProgresoId;
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
    public function getAutoliquidacionProgresoId(): int
    {
        return $this->autoliquidacionProgresoId;
    }

    
}