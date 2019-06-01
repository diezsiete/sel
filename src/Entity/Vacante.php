<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacanteRepository")
 */
class Vacante
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $requisitos;

    /**
     * @ORM\Column(type="smallint")
     */
    private $vacantesCantidad;

    /**
     * @ORM\Column(type="string", length=31, nullable=true)
     */
    private $salarioNeto;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salarioAdicion;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $salarioAdicionConcepto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $salarioPublicar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $nivelAcademicoCurso = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $idiomaPorcentaje;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $genero;

    /**
     * @ORM\Column(type="boolean")
     */
    private $publicada = true;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $empresa;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $nivel;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $subnivel;

    /**
     * @ORM\Column(type="string", length=27, nullable=true)
     */
    private $contratoTipo;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $intensidadHoraria;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $salarioRango;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nivelAcademico;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $experiencia;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $idioma;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Area", inversedBy="vacantes")
     */
    private $area;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cargo", inversedBy="vacantes")
     */
    private $cargo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ciudad", inversedBy="vacantes")
     */
    private $ciudad;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LicenciaConduccion", inversedBy="vacantes")
     */
    private $licenciaConduccion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profesion", inversedBy="vacantes")
     */
    private $profesion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VacanteRedSocial", mappedBy="vacante", orphanRemoval=true)
     */
    private $redesSociales;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usuario", inversedBy="vacantes")
     */
    private $aplicantes;

    public function __construct()
    {
        $this->area = new ArrayCollection();
        $this->cargo = new ArrayCollection();
        $this->ciudad = new ArrayCollection();
        $this->licenciaConduccion = new ArrayCollection();
        $this->profesion = new ArrayCollection();
        $this->redesSociales = new ArrayCollection();
        $this->aplicantes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getRequisitos(): ?string
    {
        return $this->requisitos;
    }

    public function setRequisitos(?string $requisitos): self
    {
        $this->requisitos = $requisitos;

        return $this;
    }

    public function getVacantesCantidad(): ?int
    {
        return $this->vacantesCantidad;
    }

    public function setVacantesCantidad(int $vacantesCantidad): self
    {
        $this->vacantesCantidad = $vacantesCantidad;

        return $this;
    }

    public function getSalarioNeto(): ?string
    {
        return $this->salarioNeto;
    }

    public function setSalarioNeto(?string $salarioNeto): self
    {
        $this->salarioNeto = $salarioNeto;

        return $this;
    }

    public function getSalarioAdicion(): ?int
    {
        return $this->salarioAdicion;
    }

    public function setSalarioAdicion(?int $salarioAdicion): self
    {
        $this->salarioAdicion = $salarioAdicion;

        return $this;
    }

    public function getSalarioAdicionConcepto(): ?string
    {
        return $this->salarioAdicionConcepto;
    }

    public function setSalarioAdicionConcepto(?string $salarioAdicionConcepto): self
    {
        $this->salarioAdicionConcepto = $salarioAdicionConcepto;

        return $this;
    }

    public function getSalarioPublicar(): ?bool
    {
        return $this->salarioPublicar;
    }

    public function setSalarioPublicar(bool $salarioPublicar): self
    {
        $this->salarioPublicar = $salarioPublicar;

        return $this;
    }

    public function getNivelAcademicoCurso(): ?bool
    {
        return $this->nivelAcademicoCurso;
    }

    public function setNivelAcademicoCurso(bool $nivelAcademicoCurso): self
    {
        $this->nivelAcademicoCurso = $nivelAcademicoCurso;

        return $this;
    }

    public function getIdiomaPorcentaje(): ?int
    {
        return $this->idiomaPorcentaje;
    }

    public function setIdiomaPorcentaje(?int $idiomaPorcentaje): self
    {
        $this->idiomaPorcentaje = $idiomaPorcentaje;

        return $this;
    }

    public function getGenero(): ?int
    {
        return $this->genero;
    }

    public function setGenero(?int $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getPublicada(): ?bool
    {
        return $this->publicada;
    }

    public function setPublicada(bool $publicada): self
    {
        $this->publicada = $publicada;

        return $this;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(?string $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getNivel(): ?string
    {
        return $this->nivel;
    }

    public function setNivel(string $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getSubnivel(): ?string
    {
        return $this->subnivel;
    }

    public function setSubnivel(string $subnivel): self
    {
        $this->subnivel = $subnivel;

        return $this;
    }

    public function getContratoTipo(): ?string
    {
        return $this->contratoTipo;
    }

    public function setContratoTipo(?string $contratoTipo): self
    {
        $this->contratoTipo = $contratoTipo;

        return $this;
    }

    public function getIntensidadHoraria(): ?string
    {
        return $this->intensidadHoraria;
    }

    public function setIntensidadHoraria(?string $intensidadHoraria): self
    {
        $this->intensidadHoraria = $intensidadHoraria;

        return $this;
    }

    public function getSalarioRango(): ?string
    {
        return $this->salarioRango;
    }

    public function setSalarioRango(string $salarioRango): self
    {
        $this->salarioRango = $salarioRango;

        return $this;
    }

    public function getNivelAcademico(): ?string
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico(string $nivelAcademico): self
    {
        $this->nivelAcademico = $nivelAcademico;

        return $this;
    }

    public function getExperiencia(): ?string
    {
        return $this->experiencia;
    }

    public function setExperiencia(string $experiencia): self
    {
        $this->experiencia = $experiencia;

        return $this;
    }

    public function getIdioma(): ?string
    {
        return $this->idioma;
    }

    public function setIdioma(?string $idioma): self
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * @return Collection|Area[]
     */
    public function getArea(): Collection
    {
        return $this->area;
    }

    public function addArea(Area $area): self
    {
        if (!$this->area->contains($area)) {
            $this->area[] = $area;
        }

        return $this;
    }

    public function removeArea(Area $area): self
    {
        if ($this->area->contains($area)) {
            $this->area->removeElement($area);
        }

        return $this;
    }

    /**
     * @return Collection|Cargo[]
     */
    public function getCargo(): Collection
    {
        return $this->cargo;
    }

    public function addCargo(Cargo $cargo): self
    {
        if (!$this->cargo->contains($cargo)) {
            $this->cargo[] = $cargo;
        }

        return $this;
    }

    public function removeCargo(Cargo $cargo): self
    {
        if ($this->cargo->contains($cargo)) {
            $this->cargo->removeElement($cargo);
        }

        return $this;
    }

    /**
     * @return Collection|Ciudad[]
     */
    public function getCiudad(): Collection
    {
        return $this->ciudad;
    }

    public function addCiudad(Ciudad $ciudad): self
    {
        if (!$this->ciudad->contains($ciudad)) {
            $this->ciudad[] = $ciudad;
        }

        return $this;
    }

    public function removeCiudad(Ciudad $ciudad): self
    {
        if ($this->ciudad->contains($ciudad)) {
            $this->ciudad->removeElement($ciudad);
        }

        return $this;
    }

    /**
     * @return Collection|LicenciaConduccion[]
     */
    public function getLicenciaConduccion(): Collection
    {
        return $this->licenciaConduccion;
    }

    public function addLicenciaConduccion(LicenciaConduccion $licenciaConduccion): self
    {
        if (!$this->licenciaConduccion->contains($licenciaConduccion)) {
            $this->licenciaConduccion[] = $licenciaConduccion;
        }

        return $this;
    }

    public function removeLicenciaConduccion(LicenciaConduccion $licenciaConduccion): self
    {
        if ($this->licenciaConduccion->contains($licenciaConduccion)) {
            $this->licenciaConduccion->removeElement($licenciaConduccion);
        }

        return $this;
    }

    /**
     * @return Collection|Profesion[]
     */
    public function getProfesion(): Collection
    {
        return $this->profesion;
    }

    public function addProfesion(Profesion $profesion): self
    {
        if (!$this->profesion->contains($profesion)) {
            $this->profesion[] = $profesion;
        }

        return $this;
    }

    public function removeProfesion(Profesion $profesion): self
    {
        if ($this->profesion->contains($profesion)) {
            $this->profesion->removeElement($profesion);
        }

        return $this;
    }

    /**
     * @return Collection|VacanteRedSocial[]
     */
    public function getRedesSociales(): Collection
    {
        return $this->redesSociales;
    }

    public function addRedesSociale(VacanteRedSocial $redesSociale): self
    {
        if (!$this->redesSociales->contains($redesSociale)) {
            $this->redesSociales[] = $redesSociale;
            $redesSociale->setVacante($this);
        }

        return $this;
    }

    public function removeRedesSociale(VacanteRedSocial $redesSociale): self
    {
        if ($this->redesSociales->contains($redesSociale)) {
            $this->redesSociales->removeElement($redesSociale);
            // set the owning side to null (unless already changed)
            if ($redesSociale->getVacante() === $this) {
                $redesSociale->setVacante(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Usuario[]
     */
    public function getAplicantes(): Collection
    {
        return $this->aplicantes;
    }

    public function addAplicante(Usuario $aplicante): self
    {
        if (!$this->aplicantes->contains($aplicante)) {
            $this->aplicantes[] = $aplicante;
        }

        return $this;
    }

    public function removeAplicante(Usuario $aplicante): self
    {
        if ($this->aplicantes->contains($aplicante)) {
            $this->aplicantes->removeElement($aplicante);
        }

        return $this;
    }
}
