<?php


namespace App\Service\NovasoftSsrs\Entity;


class NovasoftNominaDeducido
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
     * @return NovasoftNominaDeducido
     */
    public function setCodigo(string $codigo): NovasoftNominaDeducido
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
     * @return NovasoftNominaDeducido
     */
    public function setDetalle(string $detalle): NovasoftNominaDeducido
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
     * @return NovasoftNominaDeducido
     */
    public function setCantidad(string $cantidad): NovasoftNominaDeducido
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
     * @return NovasoftNominaDeducido
     */
    public function setDeducidos(string $deducidos): NovasoftNominaDeducido
    {
        $this->deducidos = $deducidos;
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
     * @return NovasoftNominaDeducido
     */
    public function setNomina(NovasoftNomina $nomina): NovasoftNominaDeducido
    {
        $this->nomina = $nomina;
        return $this;
    }
}