<?php


namespace App\Service\NovasoftSsrs\Entity;


use Doctrine\Common\Collections\ArrayCollection;

class NovasoftNomina
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
     * @var ArrayCollection|NovasoftNominaDeducido[]
     */
    private $deducidos;

    /**
     * @var ArrayCollection|NovasoftNominaDevengo[]
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
     * @return NovasoftNomina
     */
    public function setFecha(\DateTime $fecha): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setNombre(string $nombre): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setNitTercero(string $nitTercero): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setConvenioNombre(string $convenioNombre): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setPension(string $pension): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setSalud(string $salud): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setBanco(string $banco): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setCuenta(string $cuenta): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setSalario(string $salario): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setCargo(string $cargo): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setDevengadosTotal(string $devengadosTotal): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setDeducidosTotal(string $deducidosTotal): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setNeto(string $neto): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setNetoTexto(string $netoTexto): NovasoftNomina
    {
        $this->netoTexto = $netoTexto;
        return $this;
    }

    public function addDeducido(NovasoftNominaDeducido $deducido)
    {
        $this->deducidos->add($deducido);
        $deducido->setNomina($this);
    }


    public function addDevengo(NovasoftNominaDevengo $devengo)
    {
        $this->devengados->add($devengo);
        $devengo->setNomina($this);
    }

    /**
     * @return NovasoftNominaDeducido[]|ArrayCollection
     */
    public function getDeducidos()
    {
        return $this->deducidos;
    }

    /**
     * @return NovasoftNominaDevengo[]|ArrayCollection
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
     * @return NovasoftNomina
     */
    public function setBaseSalario(string $baseSalario): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setBasePension(string $basePension): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setBaseRetencion(string $baseRetencion): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setMetRetencion(string $metRetencion): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setPorcentajeRetencion(string $porcentajeRetencion): NovasoftNomina
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
     * @return NovasoftNomina
     */
    public function setDiasVacacionesPend(string $diasVacacionesPend): NovasoftNomina
    {
        $this->diasVacacionesPend = $diasVacacionesPend;
        return $this;
    }
}