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
     * @ORM\Column(type="text")
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
    private $salarioPublicar = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $nivelAcademicoCurso = 0;


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
     * @ORM\Column(type="smallint")
     */
    private $nivel;

    /**
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
     * @ORM\Column(type="smallint")
     */
    private $salarioRango;

    /**
     * @ORM\Column(type="smallint")
     */
    private $experiencia;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Cargo", inversedBy="vacantes")
     */
    private $cargo;

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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ciudad")
     * @ORM\JoinTable(name="vacante_ciudad",
     *     joinColumns={@ORM\JoinColumn(name="vacante_id", referencedColumnName="id")},
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="ciudad_id", referencedColumnName="id"),
     *          @ORM\JoinColumn(name="pais_id", referencedColumnName="pais_id"),
     *          @ORM\JoinColumn(name="dpto_id", referencedColumnName="dpto_id")
     *     }
     * )
     */
    private $ciudad;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Idioma")
     */
    private $idioma;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $idiomaDestreza;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VacanteVigencia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vigencia;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\VacanteArea", inversedBy="vacantes")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NivelAcademico")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivelAcademico;

    public function __construct()
    {
        $this->cargo = new ArrayCollection();
        $this->licenciaConduccion = new ArrayCollection();
        $this->profesion = new ArrayCollection();
        $this->redesSociales = new ArrayCollection();
        $this->aplicantes = new ArrayCollection();
        $this->ciudad = new ArrayCollection();
        $this->area = new ArrayCollection();
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

    public function getIdioma(): ?Idioma
    {
        return $this->idioma;
    }

    public function setIdioma(?Idioma $idioma): self
    {
        $this->idioma = $idioma;

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

    public function getVigencia(): ?VacanteVigencia
    {
        return $this->vigencia;
    }

    public function setVigencia(?VacanteVigencia $vigencia): self
    {
        $this->vigencia = $vigencia;

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

    public function getNivelAcademico(): ?NivelAcademico
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico(?NivelAcademico $nivelAcademico): self
    {
        $this->nivelAcademico = $nivelAcademico;

        return $this;
    }
}
