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
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $nitTercer;

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

    public function getNitTercer(): ?int
    {
        return $this->nitTercer;
    }

    public function getDesde(): ?DateTimeInterface
    {
        return $this->desde;
    }

    public function getHasta(): ?DateTimeInterface
    {
        return $this->hasta;
    }

    public function getSalarios(): ?int
    {
        return $this->salarios;
    }

    public function getCesantias(): ?int
    {
        return $this->cesantias;
    }

    public function getRepresenta(): ?int
    {
        return $this->representa;
    }

    public function getPension(): ?int
    {
        return $this->pension;
    }

    public function getOtrosIng(): ?int
    {
        return $this->otrosIng;
    }

    public function getEps(): ?int
    {
        return $this->eps;
    }

    public function getAfp(): ?int
    {
        return $this->afp;
    }

    public function getAfpVol(): ?int
    {
        return $this->afpVol;
    }

    public function getRetefuente(): ?int
    {
        return $this->retefuente;
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
}
