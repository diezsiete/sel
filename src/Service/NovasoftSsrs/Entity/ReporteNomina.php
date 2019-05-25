<?php


namespace App\Service\NovasoftSsrs\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class ReporteNomina
{
    /**
     * @var \DateTime
     */
    private $fecha;
    /**
     * @var string
     */
    private $nombre;
    /**
     * @var string
     */
    private $nitTercero;
    /**
     * @var string
     */
    private $convenioNombre;
    /**
     * @var string
     */
    private $pension;
    /**
     * @var string
     */
    private $salud;
    /**
     * @var string
     */
    private $banco;
    /**
     * @var string
     */
    private $cuenta;
    /**
     * @var string
     */
    private $salario;
    /**
     * @var string
     */
    private $cargo;
    /**
     * @var string
     */
    private $devengadosTotal;
    /**
     * @var string
     */
    private $deducidosTotal;
    /**
     * @var string
     */
    private $neto;
    /**
     * @var string
     */
    private $netoTexto;

    /**
     * @var ArrayCollection|ReporteNominaDeducido[]
     */
    private $deducidos;

    /**
     * @var ArrayCollection|ReporteNominaDevengo[]
     */
    private $devengados;
    /**
     * @var string
     */
    private $baseSalario;
    /**
     * @var string
     */
    private $basePension;
    /**
     * @var string
     */
    private $baseRetencion;
    /**
     * @var string
     */
    private $metRetencion;
    /**
     * @var string
     */
    private $porcentajeRetencion;
    /**
     * @var string
     */
    private $diasVacacionesPend;

    public function __construct()
    {
        $this->deducidos = new ArrayCollection();
        $this->devengados = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    /**
     * @param \DateTime $fecha
     * @return ReporteNomina
     */
    public function setFecha(\DateTime $fecha): ReporteNomina
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return ReporteNomina
     */
    public function setNombre(string $nombre): ReporteNomina
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getNitTercero(): string
    {
        return $this->nitTercero;
    }

    /**
     * @param string $nitTercero
     * @return ReporteNomina
     */
    public function setNitTercero(string $nitTercero): ReporteNomina
    {
        $this->nitTercero = $nitTercero;
        return $this;
    }

    /**
     * @return string
     */
    public function getConvenioNombre(): string
    {
        return $this->convenioNombre;
    }

    /**
     * @param string $convenioNombre
     * @return ReporteNomina
     */
    public function setConvenioNombre(string $convenioNombre): ReporteNomina
    {
        $this->convenioNombre = $convenioNombre;
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
     * @return ReporteNomina
     */
    public function setPension(string $pension): ReporteNomina
    {
        $this->pension = $pension;
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
     * @return ReporteNomina
     */
    public function setSalud(string $salud): ReporteNomina
    {
        $this->salud = $salud;
        return $this;
    }

    /**
     * @return string
     */
    public function getBanco(): string
    {
        return $this->banco;
    }

    /**
     * @param string $banco
     * @return ReporteNomina
     */
    public function setBanco(string $banco): ReporteNomina
    {
        $this->banco = $banco;
        return $this;
    }

    /**
     * @return string
     */
    public function getCuenta(): string
    {
        return $this->cuenta;
    }

    /**
     * @param string $cuenta
     * @return ReporteNomina
     */
    public function setCuenta(string $cuenta): ReporteNomina
    {
        $this->cuenta = $cuenta;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalario(): string
    {
        return $this->salario;
    }

    /**
     * @param string $salario
     * @return ReporteNomina
     */
    public function setSalario(string $salario): ReporteNomina
    {
        $this->salario = $salario;
        return $this;
    }

    /**
     * @return string
     */
    public function getCargo(): string
    {
        return $this->cargo;
    }

    /**
     * @param string $cargo
     * @return ReporteNomina
     */
    public function setCargo(string $cargo): ReporteNomina
    {
        $this->cargo = $cargo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDevengadosTotal(): string
    {
        return $this->devengadosTotal;
    }

    /**
     * @param string $devengadosTotal
     * @return ReporteNomina
     */
    public function setDevengadosTotal(string $devengadosTotal): ReporteNomina
    {
        $this->devengadosTotal = $devengadosTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeducidosTotal(): string
    {
        return $this->deducidosTotal;
    }

    /**
     * @param string $deducidosTotal
     * @return ReporteNomina
     */
    public function setDeducidosTotal(string $deducidosTotal): ReporteNomina
    {
        $this->deducidosTotal = $deducidosTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getNeto(): string
    {
        return $this->neto;
    }

    /**
     * @param string $neto
     * @return ReporteNomina
     */
    public function setNeto(string $neto): ReporteNomina
    {
        $this->neto = $neto;
        return $this;
    }

    /**
     * @return string
     */
    public function getNetoTexto(): string
    {
        return $this->netoTexto;
    }

    /**
     * @param string $netoTexto
     * @return ReporteNomina
     */
    public function setNetoTexto(string $netoTexto): ReporteNomina
    {
        $this->netoTexto = $netoTexto;
        return $this;
    }

    public function addDeducido(ReporteNominaDeducido $deducido)
    {
        $this->deducidos->add($deducido);
        $deducido->setNomina($this);
    }


    public function addDevengo(ReporteNominaDevengo $devengo)
    {
        $this->devengados->add($devengo);
        $devengo->setNomina($this);
    }

    /**
     * @return ReporteNominaDeducido[]|ArrayCollection
     */
    public function getDeducidos()
    {
        return $this->deducidos;
    }

    /**
     * @return ReporteNominaDevengo[]|ArrayCollection
     */
    public function getDevengados()
    {
        return $this->devengados;
    }

    /**
     * @return string
     */
    public function getBaseSalario(): string
    {
        return $this->baseSalario;
    }

    /**
     * @param string $baseSalario
     * @return ReporteNomina
     */
    public function setBaseSalario(string $baseSalario): ReporteNomina
    {
        $this->baseSalario = $baseSalario;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePension(): string
    {
        return $this->basePension;
    }

    /**
     * @param string $basePension
     * @return ReporteNomina
     */
    public function setBasePension(string $basePension): ReporteNomina
    {
        $this->basePension = $basePension;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseRetencion(): string
    {
        return $this->baseRetencion;
    }

    /**
     * @param string $baseRetencion
     * @return ReporteNomina
     */
    public function setBaseRetencion(string $baseRetencion): ReporteNomina
    {
        $this->baseRetencion = $baseRetencion;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetRetencion(): string
    {
        return $this->metRetencion;
    }

    /**
     * @param string $metRetencion
     * @return ReporteNomina
     */
    public function setMetRetencion(string $metRetencion): ReporteNomina
    {
        $this->metRetencion = $metRetencion;
        return $this;
    }

    /**
     * @return string
     */
    public function getPorcentajeRetencion(): string
    {
        return $this->porcentajeRetencion;
    }

    /**
     * @param string $porcentajeRetencion
     * @return ReporteNomina
     */
    public function setPorcentajeRetencion(string $porcentajeRetencion): ReporteNomina
    {
        $this->porcentajeRetencion = $porcentajeRetencion;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiasVacacionesPend(): string
    {
        return $this->diasVacacionesPend;
    }

    /**
     * @param string $diasVacacionesPend
     * @return ReporteNomina
     */
    public function setDiasVacacionesPend(string $diasVacacionesPend): ReporteNomina
    {
        $this->diasVacacionesPend = $diasVacacionesPend;
        return $this;
    }
}