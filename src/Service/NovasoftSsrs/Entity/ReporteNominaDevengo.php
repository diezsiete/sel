<?php


namespace App\Service\NovasoftSsrs\Entity;


class ReporteNominaDevengo
{
    /**
     * @var string
     */
    private $codigo;
    /**
     * @var string
     */
    private $detalle;
    /**
     * @var string
     */
    private $cantidad;
    /**
     * @var string
     */
    private $devengados;
    /**
     * @var ReporteNomina
     */
    private $nomina;

    /**
     * @return string
     */
    public function getCodigo(): string
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     * @return ReporteNominaDevengo
     */
    public function setCodigo(string $codigo): ReporteNominaDevengo
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetalle(): string
    {
        return $this->detalle;
    }

    /**
     * @param string $detalle
     * @return ReporteNominaDevengo
     */
    public function setDetalle(string $detalle): ReporteNominaDevengo
    {
        $this->detalle = $detalle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCantidad(): string
    {
        return $this->cantidad;
    }

    /**
     * @param string $cantidad
     * @return ReporteNominaDevengo
     */
    public function setCantidad(string $cantidad): ReporteNominaDevengo
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * @return string
     */
    public function getDevengados(): string
    {
        return $this->devengados;
    }

    /**
     * @param string $devengados
     * @return ReporteNominaDevengo
     */
    public function setDevengados(string $devengados): ReporteNominaDevengo
    {
        $this->devengados = $devengados;
        return $this;
    }

    /**
     * @return ReporteNomina
     */
    public function getNomina(): ReporteNomina
    {
        return $this->nomina;
    }

    /**
     * @param ReporteNomina $nomina
     * @return ReporteNominaDevengo
     */
    public function setNomina(ReporteNomina $nomina): ReporteNominaDevengo
    {
        $this->nomina = $nomina;
        return $this;
    }
}