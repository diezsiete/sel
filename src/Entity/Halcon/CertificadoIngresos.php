<?php

namespace App\Entity\Halcon;

use DateTimeInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\CertificadoIngresosRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CertificadoIngresos
{
    /**
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="App\Entity\Halcon\Empresa", fetch="EAGER")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="usuario")
     * @var Empresa|null
     */
    private $empresa;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=8)
     */
    private $noContrat;

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=4)
     */
    private $ano;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\Tercero")
     * @ORM\JoinColumn(name="nit_tercer", referencedColumnName="nit_tercer")
     * @var Tercero|null
     */
    private $tercero;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $desde;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $hasta;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salarios;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cesantias;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $representa;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pension;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otrosIng;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $eps;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $afp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $afpVol;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $retefuente;

    /**
     * @return Empresa|null
     */
    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function getNoContrat(): ?string
    {
        return $this->noContrat;
    }

    public function getAno(): ?string
    {
        return $this->ano;
    }

    /**
     * @return Tercero
     */
    public function getTercero()
    {
        return $this->tercero;
    }

    public function getDesde(): ?DateTimeInterface
    {
        return $this->desde;
    }

    public function getHasta(): ?DateTimeInterface
    {
        return $this->hasta;
    }

    public function getSalarios($format = true)
    {
        return $format ? number_format($this->salarios) : $this->salarios;
    }

    public function getCesantias($format = true)
    {
        return $format ? number_format($this->cesantias) : $this->cesantias;
    }

    public function getRepresenta($format = true)
    {
        return $format ? number_format($this->representa) : $this->representa;
    }

    public function getPension($format = true)
    {
        return $format ? number_format($this->pension) : $this->pension;
    }

    public function getOtrosIng($format = true)
    {
        return $format ? number_format($this->otrosIng) : $this->otrosIng;
    }

    public function getEps($format = true)
    {
        return $format ? number_format($this->eps) : $this->eps;
    }

    public function getAfp($format = true)
    {
        return $format ? number_format($this->afp) : $this->afp;
    }

    public function getAfpVol($format = true)
    {
        return $format ? number_format($this->afpVol) : $this->afpVol;
    }

    public function getRetefuente($format = true)
    {
        return $format ? number_format($this->retefuente) : $this->retefuente;
    }

    /**
     * @ORM\PostLoad
     */
    public function loadNullDoctor()
    {
        try {
            $this->empresa->getCompania();
        } catch (EntityNotFoundException $e) {
            $this->empresa = null;
        }
    }

    public function getCompaniaNombre()
    {
        if(!$this->empresa) {
            return "SIN DEFINIR";
        }
        return $this->empresa->getCompania()->getNombre();
    }

    public function getCompaniaNit()
    {
        if(!$this->empresa) {
            return "SIN DEFINIR";
        }
        return $this->empresa->getCompania()->getNit();
    }

    public function terceroTipoNit()
    {
        switch ($this->tercero->getTipoNit()) {
            case 'EXT':
                $tipo = 'EXT';
                break;
            case 'TID':
                $tipo = 'TID';
                break;
            default:
                $tipo = 13;
        }
        return $tipo;
    }

    public function getTotalIngresosBrutos($format = true)
    {
        $totalIngresosBrutos = $this->salarios + $this->cesantias + $this->representa + $this->pension + $this->otrosIng;
        return $format ? number_format($totalIngresosBrutos) : $totalIngresosBrutos;
    }
}
