<?php


namespace App\Service\NovasoftSsrs\Entity;


class ReporteNominaDeducido
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
    private $deducidos;
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
     * @return ReporteNominaDeducido
     */
    public function setCodigo(string $codigo): ReporteNominaDeducido
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
     * @return ReporteNominaDeducido
     */
    public function setDetalle(string $detalle): ReporteNominaDeducido
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
     * @return ReporteNominaDeducido
     */
    public function setCantidad(string $cantidad): ReporteNominaDeducido
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeducidos(): string
    {
        return $this->deducidos;
    }

    /**
     * @param string $deducidos
     * @return ReporteNominaDeducido
     */
    public function setDeducidos(string $deducidos): ReporteNominaDeducido
    {
        $this->deducidos = $deducidos;
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
     * @return ReporteNominaDeducido
     */
    public function setNomina(ReporteNomina $nomina): ReporteNominaDeducido
    {
        $this->nomina = $nomina;
        return $this;
    }
}