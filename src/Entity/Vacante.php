<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VacanteRepository")
 */
class Vacante
{
    use TimestampableEntity;

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
     * @Assert\NotBlank(message="Ingrese titulo de la vacante")
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @Assert\NotBlank(message="Ingrese descripción de la vacante")
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @Assert\NotBlank(message="Ingrese requisitos de la vacante")
     * @ORM\Column(type="text")
     */
    private $requisitos;

    /**
     * @Assert\Count(min=1, max=100, minMessage="Agregar al menos un area")
     * @ORM\ManyToMany(targetEntity="App\Entity\VacanteArea", inversedBy="vacantes")
     */
    private $area;

    /**
     * @Assert\Count(min=1, max=100, minMessage="Agregar al menos un cargo")
     * @ORM\ManyToMany(targetEntity="App\Entity\Cargo", inversedBy="vacantes")
     */
    private $cargo;

    /**
     * @Assert\NotBlank(message="Ingrese nivel")
     * @ORM\Column(type="smallint")
     */
    private $nivel;

    /**
     * @Assert\NotBlank(message="Ingrese subnivel")
     * @ORM\Column(type="smallint")
     */
    private $subnivel;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $contratoTipo;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $intensidadHoraria;

    /**
     * @Assert\NotBlank(message="Ingrese cantidad de vacantes disponibles")
     * @Assert\Positive(message="Ingrese número mayor a 0")
     * @ORM\Column(type="smallint")
     */
    private $vacantesCantidad = 1;

    /**
     * @Assert\NotBlank(message="Ingrese rango de salario")
     * @ORM\Column(type="smallint")
     */
    private $salarioRango;

    /**
     * @ORM\Column(type="boolean")
     */
    private $salarioPublicar = true;

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
    private $nivelAcademicoCurso = false;

    /**
     * @Assert\Count(min=1, max=100, minMessage="Agregar al menos una profesión")
     * @ORM\ManyToMany(targetEntity="App\Entity\Profesion", inversedBy="vacantes")
     */
    private $profesion;

    /**
     * @ORM\Column(type="smallint")
     */
    private $experiencia;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $idiomaDestreza;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $genero;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\LicenciaConduccion", inversedBy="vacantes")
     */
    private $licenciaConduccion;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $empresa;


    /**
     * @ORM\Column(type="boolean")
     */
    private $publicada = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VacanteRedSocial", mappedBy="vacante", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $redesSociales;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Usuario", inversedBy="vacantes")
     */
    private $aplicantes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ciudad")
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $idiomaCodigo;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $nivelAcademico;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activa = true;

    /**
     * @ORM\Column(type="smallint")
     */
    private $vigencia = 8;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivada = false;


    public function __construct()
    {
        $this->cargo = new ArrayCollection();
        $this->licenciaConduccion = new ArrayCollection();
        $this->profesion = new ArrayCollection();
        $this->redesSociales = new ArrayCollection();
        $this->aplicantes = new ArrayCollection();
        $this->area = new ArrayCollection();
        $this->ciudad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?UserInterface $usuario): self
    {
        $this->usuario = $usuario;

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

    public function getNivel(): ?int
    {
        return $this->nivel;
    }

    /**
     * @param int $nivel
     * @return Vacante
     */
    public function setNivel($nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getSubnivel(): ?int
    {
        return $this->subnivel;
    }

    /**
     * @param int $subnivel
     * @return Vacante
     */
    public function setSubnivel($subnivel): self
    {
        $this->subnivel = $subnivel;

        return $this;
    }

    public function getContratoTipo(): ?int
    {
        return $this->contratoTipo;
    }

    /**
     * @param int $contratoTipo
     * @return Vacante
     */
    public function setContratoTipo($contratoTipo): self
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

    public function getSalarioRango(): ?int
    {
        return $this->salarioRango;
    }

    /**
     * @param int $salarioRango
     * @return Vacante
     */
    public function setSalarioRango($salarioRango): self
    {
        $this->salarioRango = $salarioRango;

        return $this;
    }


    public function getExperiencia(): ?int
    {
        return $this->experiencia;
    }

    public function setExperiencia(int $experiencia): self
    {
        $this->experiencia = $experiencia;

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

    public function addRedesSocial(VacanteRedSocial $redesSociale): self
    {
        if (!$this->redesSociales->contains($redesSociale)) {
            $this->redesSociales[] = $redesSociale;
            $redesSociale->setVacante($this);
        }

        return $this;
    }

    public function removeRedesSocial(VacanteRedSocial $redesSociale): self
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


    public function getIdiomaDestreza(): ?string
    {
        return $this->idiomaDestreza;
    }

    public function setIdiomaDestreza(?string $idiomaDestreza): self
    {
        $this->idiomaDestreza = $idiomaDestreza;

        return $this;
    }

    /**
     * @return Collection|VacanteArea[]
     */
    public function getArea(): Collection
    {
        return $this->area;
    }

    public function addArea(VacanteArea $area): self
    {
        if (!$this->area->contains($area)) {
            $this->area[] = $area;
        }

        return $this;
    }

    public function removeArea(VacanteArea $area): self
    {
        if ($this->area->contains($area)) {
            $this->area->removeElement($area);
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

    public function getIdiomaCodigo(): ?string
    {
        return $this->idiomaCodigo;
    }

    public function setIdiomaCodigo(?string $idiomaCodigo): self
    {
        $this->idiomaCodigo = $idiomaCodigo;

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

    public function getActiva(): ?bool
    {
        return $this->activa;
    }

    public function setActiva(bool $activa): self
    {
        $this->activa = $activa;

        return $this;
    }

    public function getVigencia(): ?int
    {
        return $this->vigencia;
    }

    public function setVigencia(int $vigencia): self
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    public function getArchivada(): ?bool
    {
        return $this->archivada;
    }

    public function setArchivada(bool $archivada): self
    {
        $this->archivada = $archivada;

        return $this;
    }
}
