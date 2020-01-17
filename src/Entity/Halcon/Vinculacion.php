<?php

namespace App\Entity\Halcon;

use DateTimeInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\VinculacionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Vinculacion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=8)
     */
    private $noContrat;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $nitTercer;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ingreso;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $retiro;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\Empresa")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="usuario")
     * @var Empresa|null
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $centroCos;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $oficina;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $sueldoMes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxTransp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $aumento;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $estado;



    public function getNoContrat(): ?string
    {
        return $this->noContrat;
    }

    public function getNitTercer(): ?int
    {
        return $this->nitTercer;
    }

    public function getIngreso(): ?DateTimeInterface
    {
        return $this->ingreso;
    }

    public function getRetiro(): ?string
    {
        return $this->retiro;
    }

    public function getCentroCos(): ?string
    {
        return $this->centroCos;
    }

    public function getOficina(): ?string
    {
        return $this->oficina;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function getSueldoMes(): ?int
    {
        return $this->sueldoMes;
    }

    public function getAuxTransp(): ?int
    {
        return $this->auxTransp;
    }

    public function getAumento(): ?DateTimeInterface
    {
        return $this->aumento;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * @return Empresa|null
     */
    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    /**
     * @ORM\PostLoad
     */
    public function loadNullEmpresa()
    {
        try {
            $this->empresa->getCompania();
        } catch (EntityNotFoundException $e) {
            $this->empresa = null;
        }
    }
}
