<?php


namespace App\Service\Configuracion;


class SsrsDb
{
    /**
     * @var string
     */
    private $nombre;
    /**
     * @var bool
     */
    private $convenios;

    private $reportes;

    public function __construct($nombre, $ssrsDbConfig)
    {
        $this->nombre = $nombre;
        $this->convenios = $ssrsDbConfig['convenios'];
        $this->reportes = $ssrsDbConfig['reportes'];
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @return bool
     */
    public function hasConvenios(): bool
    {
        return $this->convenios;
    }

    public function getReporteEmpleado()
    {
        return $this->reportes['empleado'];
    }
}