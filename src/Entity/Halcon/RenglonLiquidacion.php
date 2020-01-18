<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\RenglonLiquidacionRepository")
 */
class RenglonLiquidacion
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\CabezaLiquidacion", inversedBy="renglones")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="no_contrat", referencedColumnName="no_contrat"),
     *  @ORM\JoinColumn(name="liq_defini", referencedColumnName="liq_defini")
     * })
     * @var CabezaLiquidacion
     */
    private $cabeza;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=6)
     */
    private $concepto;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $nomConcep;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     */
    private $cuenta;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $novedad;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $devengado;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $deducido;


    public function getConcepto(): ?string
    {
        return $this->concepto;
    }


    public function getNomConcep(): ?string
    {
        return $this->nomConcep;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function getNovedad($format = true)
    {
        return $format ? number_format($this->novedad) : $this->novedad;
    }

    public function getDevengado($format = true)
    {
        return $format ? number_format($this->devengado) : $this->devengado;
    }

    public function getDeducido($format = true)
    {
        return $format ? number_format($this->deducido) : $this->deducido;
    }

    /**
     * @return CabezaLiquidacion
     */
    public function getCabeza(): CabezaLiquidacion
    {
        return $this->cabeza;
    }
}
