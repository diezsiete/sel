<?php


namespace App\Service\NovasoftSsrs\Entity;


class ReporteLiquidacion
{
    /**
     * @var string
     */
    private $identificacion;
    /**
     * @var string
     */
    private $nombreCompleto;
    /**
     * @var string
     */
    private $codigoSucursal;
    /**
     * @var string
     */
    private $nombreSucursal;
    /**
     * @var string
     */
    private $centroCosto;
    /**
     * @var string
     */
    private $nombreCentroCosto;
    /**
     * @var string
     */
    private $regimenCesantias;
    /**
     * @var string
     */
    private $tipoContrato;
    /**
     * @var string
     */
    private $numeroContrato;
    /**
     * @var string
     */
    private $pension;
    /**
     * @var string
     */
    private $ultimoCargo;
    /**
     * @var string
     */
    private $causaTerminacionContrato;
    /**
     * @var string
     */
    private $salud;
    /**
     * @var \DateTime
     */
    private $fechaIngreso;
    /**
     * @var \DateTime
     */
    private $fechaRetiro;
    /**
     * @var string
     */
    private $diasTotales;
    /**
     * @var string
     */
    private $diasLicencia;
    /**
     * @var string
     */
    private $ultimoSueldo;
    /**
     * @var string
     */
    private $baseCesantias;
    /**
     * @var string
     */
    private $basePrima;
    /**
     * @var string
     */
    private $baseVacaciones;

    /**
     * @return string
     */
    public function getIdentificacion(): string
    {
        return $this->identificacion;
    }

    /**
     * @param string $identificacion
     * @return ReporteLiquidacion
     */
    public function setIdentificacion(string $identificacion): ReporteLiquidacion
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombreCompleto(): string
    {
        return $this->nombreCompleto;
    }

    /**
     * @param string $nombreCompleto
     * @return ReporteLiquidacion
     */
    public function setNombreCompleto(string $nombreCompleto): ReporteLiquidacion
    {
        $this->nombreCompleto = $nombreCompleto;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoSucursal(): string
    {
        return $this->codigoSucursal;
    }

    /**
     * @param string $codigoSucursal
     * @return ReporteLiquidacion
     */
    public function setCodigoSucursal(string $codigoSucursal): ReporteLiquidacion
    {
        $this->codigoSucursal = $codigoSucursal;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombreSucursal(): string
    {
        return $this->nombreSucursal;
    }

    /**
     * @param string $nomSucursal
     * @return ReporteLiquidacion
     */
    public function setNombreSucursal(string $nomSucursal): ReporteLiquidacion
    {
        $this->nombreSucursal = $nomSucursal;
        return $this;
    }

    /**
     * @return string
     */
    public function getCentroCosto(): string
    {
        return $this->centroCosto;
    }

    /**
     * @param string $centroCosto
     * @return ReporteLiquidacion
     */
    public function setCentroCosto(string $centroCosto): ReporteLiquidacion
    {
        $this->centroCosto = $centroCosto;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombreCentroCosto(): string
    {
        return $this->nombreCentroCosto;
    }

    /**
     * @param string $nombreCentroCosto
     * @return ReporteLiquidacion
     */
    public function setNombreCentroCosto(string $nombreCentroCosto): ReporteLiquidacion
    {
        $this->nombreCentroCosto = $nombreCentroCosto;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegimenCesantias(): string
    {
        return $this->regimenCesantias;
    }

    /**
     * @param string $regimenCesantias
     * @return ReporteLiquidacion
     */
    public function setRegimenCesantias(string $regimenCesantias): ReporteLiquidacion
    {
        $this->regimenCesantias = $regimenCesantias;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipoContrato(): string
    {
        return $this->tipoContrato;
    }

    /**
     * @param string $tipoContrato
     * @return ReporteLiquidacion
     */
    public function setTipoContrato(string $tipoContrato): ReporteLiquidacion
    {
        $this->tipoContrato = $tipoContrato;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroContrato(): string
    {
        return $this->numeroContrato;
    }

    /**
     * @param string $numeroContrato
     * @return ReporteLiquidacion
     */
    public function setNumeroContrato(string $numeroContrato): ReporteLiquidacion
    {
        $this->numeroContrato = $numeroContrato;
        return $this;
    }

    /**
     * @return string
     */
    public function getPension(): string
    {
        return $this->pension;
    }

    /**
     * @param string $pension
     * @return ReporteLiquidacion
     */
    public function setPension(string $pension): ReporteLiquidacion
    {
        $this->pension = $pension;
        return $this;
    }

    /**
     * @return string
     */
    public function getUltimoCargo(): string
    {
        return $this->ultimoCargo;
    }

    /**
     * @param string $ultimoCargo
     * @return ReporteLiquidacion
     */
    public function setUltimoCargo(string $ultimoCargo): ReporteLiquidacion
    {
        $this->ultimoCargo = $ultimoCargo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCausaTerminacionContrato(): string
    {
        return $this->causaTerminacionContrato;
    }

    /**
     * @param string $causaTerminacionContrato
     * @return ReporteLiquidacion
     */
    public function setCausaTerminacionContrato(string $causaTerminacionContrato): ReporteLiquidacion
    {
        $this->causaTerminacionContrato = $causaTerminacionContrato;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalud(): string
    {
        return $this->salud;
    }

    /**
     * @param string $salud
     * @return ReporteLiquidacion
     */
    public function setSalud(string $salud): ReporteLiquidacion
    {
        $this->salud = $salud;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaIngreso(): \DateTime
    {
        return $this->fechaIngreso;
    }

    /**
     * @param \DateTime $fechaIngreso
     * @return ReporteLiquidacion
     */
    public function setFechaIngreso(\DateTime $fechaIngreso): ReporteLiquidacion
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaRetiro(): \DateTime
    {
        return $this->fechaRetiro;
    }

    /**
     * @param \DateTime $fechaRetiro
     * @return ReporteLiquidacion
     */
    public function setFechaRetiro(\DateTime $fechaRetiro): ReporteLiquidacion
    {
        $this->fechaRetiro = $fechaRetiro;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiasTotales(): string
    {
        return $this->diasTotales;
    }

    /**
     * @param string $diasTotales
     * @return ReporteLiquidacion
     */
    public function setDiasTotales(string $diasTotales): ReporteLiquidacion
    {
        $this->diasTotales = $diasTotales;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiasLicencia(): string
    {
        return $this->diasLicencia;
    }

    /**
     * @param string $diasLicencia
     * @return ReporteLiquidacion
     */
    public function setDiasLicencia(string $diasLicencia): ReporteLiquidacion
    {
        $this->diasLicencia = $diasLicencia;
        return $this;
    }

    /**
     * @return string
     */
    public function getUltimoSueldo(): string
    {
        return $this->ultimoSueldo;
    }

    /**
     * @param string $ultimoSueldo
     * @return ReporteLiquidacion
     */
    public function setUltimoSueldo(string $ultimoSueldo): ReporteLiquidacion
    {
        $this->ultimoSueldo = $ultimoSueldo;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseCesantias(): string
    {
        return $this->baseCesantias;
    }

    /**
     * @param string $baseCesantias
     * @return ReporteLiquidacion
     */
    public function setBaseCesantias(string $baseCesantias): ReporteLiquidacion
    {
        $this->baseCesantias = $baseCesantias;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePrima(): string
    {
        return $this->basePrima;
    }

    /**
     * @param string $basePrima
     * @return ReporteLiquidacion
     */
    public function setBasePrima(string $basePrima): ReporteLiquidacion
    {
        $this->basePrima = $basePrima;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseVacaciones(): string
    {
        return $this->baseVacaciones;
    }

    /**
     * @param string $baseVacaciones
     * @return ReporteLiquidacion
     */
    public function setBaseVacaciones(string $baseVacaciones): ReporteLiquidacion
    {
        $this->baseVacaciones = $baseVacaciones;
        return $this;
    }
}