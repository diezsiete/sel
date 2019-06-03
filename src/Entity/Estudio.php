<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstudioRepository")
 */
class Estudio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv", inversedBy="estudios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hv;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EstudioCodigo")
     * @ORM\JoinColumn(nullable=false)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EstudioInstituto")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instituto;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fin;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $institutoNombreAlt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anoEstudio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $horasEstudio;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $graduado;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $semestresAprobados;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $cancelo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $numeroTarjeta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHv(): ?Hv
    {
        return $this->hv;
    }

    public function setHv(?Hv $hv): self
    {
        $this->hv = $hv;

        return $this;
    }

    public function getCodigo(): ?EstudioCodigo
    {
        return $this->codigo;
    }

    public function setCodigo(?EstudioCodigo $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getInstituto(): ?EstudioInstituto
    {
        return $this->instituto;
    }

    public function setInstituto(?EstudioInstituto $instituto): self
    {
        $this->instituto = $instituto;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getInstitutoNombreAlt(): ?string
    {
        return $this->institutoNombreAlt;
    }

    public function setInstitutoNombreAlt(?string $institutoNombreAlt): self
    {
        $this->institutoNombreAlt = $institutoNombreAlt;

        return $this;
    }

    public function getAnoEstudio(): ?int
    {
        return $this->anoEstudio;
    }

    public function setAnoEstudio(?int $anoEstudio): self
    {
        $this->anoEstudio = $anoEstudio;

        return $this;
    }

    public function getHorasEstudio(): ?int
    {
        return $this->horasEstudio;
    }

    public function setHorasEstudio(?int $horasEstudio): self
    {
        $this->horasEstudio = $horasEstudio;

        return $this;
    }

    public function getGraduado(): ?bool
    {
        return $this->graduado;
    }

    public function setGraduado(?bool $graduado): self
    {
        $this->graduado = $graduado;

        return $this;
    }

    public function getSemestresAprobados(): ?int
    {
        return $this->semestresAprobados;
    }

    public function setSemestresAprobados(?int $semestresAprobados): self
    {
        $this->semestresAprobados = $semestresAprobados;

        return $this;
    }

    public function getCancelo(): ?bool
    {
        return $this->cancelo;
    }

    public function setCancelo(?bool $cancelo): self
    {
        $this->cancelo = $cancelo;

        return $this;
    }

    public function getNumeroTarjeta(): ?string
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(?string $numeroTarjeta): self
    {
        $this->numeroTarjeta = $numeroTarjeta;

        return $this;
    }
}
