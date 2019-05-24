<?php


namespace App\Service\NovasoftSsrs\Entity;


class NovasoftNominaDevengo
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
     * @var NovasoftNomina
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
     * @return NovasoftNominaDevengo
     */
    public function setCodigo(string $codigo): NovasoftNominaDevengo
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
     * @return NovasoftNominaDevengo
     */
    public function setDetalle(string $detalle): NovasoftNominaDevengo
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
     * @return NovasoftNominaDevengo
     */
    public function setCantidad(string $cantidad): NovasoftNominaDevengo
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
     * @return NovasoftNominaDevengo
     */
    public function setDevengados(string $devengados): NovasoftNominaDevengo
    {
        $this->devengados = $devengados;
        return $this;
    }

    /**
     * @return NovasoftNomina
     */
    public function getNomina(): NovasoftNomina
    {
        return $this->nomina;
    }

    /**
     * @param NovasoftNomina $nomina
     * @return NovasoftNominaDevengo
     */
    public function setNomina(NovasoftNomina $nomina): NovasoftNominaDevengo
    {
        $this->nomina = $nomina;
        return $this;
    }
}