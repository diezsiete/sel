<?php


namespace App\Entity\Novasoft\Report;

use App\Entity\Main\Usuario;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Novasoft\Report\LiquidacionContratoRepository")
 * @ORM\Table(name="novasoft_liquidacion_contrato")
 */
class LiquidacionContrato extends ServicioEmpleadosReport
{
    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $identificacion;
    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     */
    private $nombreCompleto;
    /**
     * @var string
     * @ORM\Column(type="string", length=14)
     */
    private $codigoSucursal;
    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    private $nombreSucursal;
    /**
     * @var string
     * @ORM\Column(type="string", length=14)
     */
    private $centroCosto;
    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $nombreCentroCosto;
    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    private $regimenCesantias;
    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $tipoContrato;
    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $numeroContrato;
    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    private $pension;
    /**
     * @var string
     * @ORM\Column(type="string", length=140)
     */
    private $ultimoCargo;
    /**
     * @var string
     * @ORM\Column(type="string", length=70)
     */
    private $causaTerminacionContrato;
    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     */
    private $salud;
    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $fechaIngreso;
    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $fechaRetiro;
    /**
     * @var string
     * @ORM\Column(type="string", length=14)
     */
    private $diasTotales;
    /**
     * @var string
     * @ORM\Column(type="string", length=6)
     */
    private $diasLicencia;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $ultimoSueldo;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $baseCesantias;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $basePrima;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
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
     * @return LiquidacionContrato
     */
    public function setIdentificacion(string $identificacion): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setNombreCompleto(string $nombreCompleto): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setCodigoSucursal(string $codigoSucursal): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setNombreSucursal(string $nomSucursal): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setCentroCosto(string $centroCosto): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setNombreCentroCosto(string $nombreCentroCosto): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setRegimenCesantias(string $regimenCesantias): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setTipoContrato(string $tipoContrato): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setNumeroContrato(string $numeroContrato): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setPension(string $pension): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setUltimoCargo(string $ultimoCargo): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setCausaTerminacionContrato(string $causaTerminacionContrato): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setSalud(string $salud): LiquidacionContrato
    {
        $this->salud = $salud;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaIngreso(): DateTime
    {
        return $this->fechaIngreso;
    }

    /**
     * @param DateTime $fechaIngreso
     * @return LiquidacionContrato
     */
    public function setFechaIngreso(DateTime $fechaIngreso): LiquidacionContrato
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFechaRetiro(): DateTime
    {
        return $this->fechaRetiro;
    }

    /**
     * @param DateTime $fechaRetiro
     * @return LiquidacionContrato
     */
    public function setFechaRetiro(DateTime $fechaRetiro): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setDiasTotales(string $diasTotales): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setDiasLicencia(string $diasLicencia): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setUltimoSueldo(string $ultimoSueldo): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setBaseCesantias(string $baseCesantias): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setBasePrima(string $basePrima): LiquidacionContrato
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
     * @return LiquidacionContrato
     */
    public function setBaseVacaciones(string $baseVacaciones): LiquidacionContrato
    {
        $this->baseVacaciones = $baseVacaciones;
        return $this;
    }
}