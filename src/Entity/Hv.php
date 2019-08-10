<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HvRepository")
 */
class Hv implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Usuario", cascade={"persist", "remove"})
     * @Groups({"scraper", "scraper-hv"})
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Ingrese pais de nacimiento")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nacPais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nacDpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nacCiudad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Ingrese pais de identificación")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $identPais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $identDpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $identCiudad;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $genero;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $estadoCivil;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $resiPais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Dpto")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $resiDpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ciudad")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $resiCiudad;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Assert\NotNull(message="Ingrese barrio")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $barrio;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Assert\NotNull(message="Ingrese dirección")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=2)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $grupoSanguineo;

    /**
     * @ORM\Column(type="string", length=1)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $factorRh;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Email(message="Ingrese un email valido")
     * @Groups({"scraper", "scraper-hv"})
     */
    private $emailAlt;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $aspiracionSueldo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $estatura;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $peso;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $personasCargo;

    /**
     * @ORM\Column(type="string", length=2)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $identificacionTipo;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lmilitarClase;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $lmilitarNumero;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lmilitarDistrito;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $presupuestoMensual;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deudas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deudasConcepto;

    /**
     * @ORM\Column(type="string", length=3)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $nivelAcademico;

    /**
     * @ORM\Column(type="string", length=17, nullable=true)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=17, nullable=true)
     * @Groups({"scraper", "scraper-hv"})
     */
    private $celular;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Estudio", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $estudios;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Experiencia", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $experiencia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Familiar", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $familiares;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Idioma", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $idiomas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RedSocial", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $redesSociales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Referencia", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $referencias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vivienda", mappedBy="hv", orphanRemoval=true)
     * @Groups({"scraper"})
     */
    private $viviendas;

    /**
     * @var HvAdjunto
     * @ORM\OneToOne(targetEntity="App\Entity\HvAdjunto", mappedBy="hv", cascade={"persist", "remove"})
     */
    private $adjunto;

    /**
     * @ORM\Column(type="boolean")
     */
    private $scraper = false;

    public function __construct()
    {
        $this->estudios = new ArrayCollection();
        $this->experiencia = new ArrayCollection();
        $this->familiares = new ArrayCollection();
        $this->idiomas = new ArrayCollection();
        $this->redesSociales = new ArrayCollection();
        $this->referencias = new ArrayCollection();
        $this->viviendas = new ArrayCollection();
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

    public function getNacPais(): ?Pais
    {
        return $this->nacPais;
    }

    public function setNacPais(?Pais $nacPais): self
    {
        $this->nacPais = $nacPais;

        return $this;
    }

    public function getNacDpto(): ?Dpto
    {
        return $this->nacDpto;
    }

    public function setNacDpto(?Dpto $nacDpto): self
    {
        $this->nacDpto = $nacDpto;

        return $this;
    }

    public function getNacCiudad(): ?Ciudad
    {
        return $this->nacCiudad;
    }

    public function setNacCiudad(?Ciudad $nacCiudad): self
    {
        $this->nacCiudad = $nacCiudad;

        return $this;
    }

    public function getIdentPais(): ?Pais
    {
        return $this->identPais;
    }

    public function setIdentPais(?Pais $identPais): self
    {
        $this->identPais = $identPais;

        return $this;
    }

    public function getIdentDpto(): ?Dpto
    {
        return $this->identDpto;
    }

    public function setIdentDpto(?Dpto $identDpto): self
    {
        $this->identDpto = $identDpto;

        return $this;
    }

    public function getIdentCiudad(): ?Ciudad
    {
        return $this->identCiudad;
    }

    public function setIdentCiudad(?Ciudad $identCiudad): self
    {
        $this->identCiudad = $identCiudad;

        return $this;
    }

    public function getGenero(): ?int
    {
        return $this->genero;
    }

    public function setGenero(int $genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getEstadoCivil(): ?int
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(int $estadoCivil): self
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    public function getResiPais(): ?Pais
    {
        return $this->resiPais;
    }

    public function setResiPais(?Pais $resiPais): self
    {
        $this->resiPais = $resiPais;

        return $this;
    }

    public function getResiDpto(): ?Dpto
    {
        return $this->resiDpto;
    }

    public function setResiDpto(?Dpto $resiDpto): self
    {
        $this->resiDpto = $resiDpto;

        return $this;
    }

    public function getResiCiudad(): ?Ciudad
    {
        return $this->resiCiudad;
    }

    public function setResiCiudad(?Ciudad $resiCiudad): self
    {
        $this->resiCiudad = $resiCiudad;

        return $this;
    }

    public function getBarrio(): ?string
    {
        return $this->barrio;
    }

    public function setBarrio(?string $barrio): self
    {
        $this->barrio = $barrio;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getGrupoSanguineo()
    {
        return $this->grupoSanguineo;
    }

    public function setGrupoSanguineo($grupoSanguineo): self
    {
        $this->grupoSanguineo = $grupoSanguineo;

        return $this;
    }

    public function getFactorRh()
    {
        return $this->factorRh;
    }

    public function setFactorRh($factorRh): self
    {
        $this->factorRh = $factorRh;

        return $this;
    }

    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad($nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getEmailAlt(): ?string
    {
        return $this->emailAlt;
    }

    public function setEmailAlt(?string $emailAlt): self
    {
        $this->emailAlt = $emailAlt;

        return $this;
    }

    public function getAspiracionSueldo(): ?int
    {
        return $this->aspiracionSueldo;
    }

    public function setAspiracionSueldo(?int $aspiracionSueldo): self
    {
        $this->aspiracionSueldo = $aspiracionSueldo;

        return $this;
    }

    public function getEstatura(): ?float
    {
        return $this->estatura;
    }

    public function setEstatura(?float $estatura): self
    {
        $this->estatura = $estatura;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(?float $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getPersonasCargo(): ?int
    {
        return $this->personasCargo;
    }

    public function setPersonasCargo(?int $personasCargo): self
    {
        $this->personasCargo = $personasCargo;

        return $this;
    }

    public function getAdjunto(): ?HvAdjunto
    {
        return $this->adjunto;
    }

    public function setAdjunto(HvAdjunto $adjunto): self
    {
        $this->adjunto = $adjunto;

        // set the owning side of the relation if necessary
        if ($this !== $adjunto->getHv()) {
            $adjunto->setHv($this);
        }

        return $this;
    }

    /**
     * @return Collection|Estudio[]
     */
    public function getEstudios(): Collection
    {
        return $this->estudios;
    }

    public function addEstudio(Estudio $estudio): self
    {
        if (!$this->estudios->contains($estudio)) {
            $this->estudios[] = $estudio;
            $estudio->setHv($this);
        }

        return $this;
    }

    public function removeEstudio(Estudio $estudio): self
    {
        if ($this->estudios->contains($estudio)) {
            $this->estudios->removeElement($estudio);
            // set the owning side to null (unless already changed)
            if ($estudio->getHv() === $this) {
                $estudio->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experiencia[]
     */
    public function getExperiencia(): Collection
    {
        return $this->experiencia;
    }

    public function addExperiencia(Experiencia $experiencia): self
    {
        if (!$this->experiencia->contains($experiencia)) {
            $this->experiencia[] = $experiencia;
            $experiencia->setHv($this);
        }

        return $this;
    }

    public function removeExperiencia(Experiencia $experiencia): self
    {
        if ($this->experiencia->contains($experiencia)) {
            $this->experiencia->removeElement($experiencia);
            // set the owning side to null (unless already changed)
            if ($experiencia->getHv() === $this) {
                $experiencia->setHv(null);
            }
        }

        return $this;
    }

    public function getIdentificacionTipo(): ?string
    {
        return $this->identificacionTipo;
    }

    public function setIdentificacionTipo(string $identTipo): self
    {
        $this->identificacionTipo = $identTipo;

        return $this;
    }

    /**
     * @return Collection|Familiar[]
     */
    public function getFamiliares(?string $parentesco = null): Collection
    {
        if($parentesco) {
            $familiares = new ArrayCollection();
            foreach($this->familiares as $familiar) {
                if($familiar->getParentesco() === $parentesco){
                    $familiares->add($familiar);
                }
            }
        }else{
            $familiares = $this->familiares;
        }
        return $familiares;
    }

    public function addFamiliare(Familiar $familiare): self
    {
        if (!$this->familiares->contains($familiare)) {
            $this->familiares[] = $familiare;
            $familiare->setHv($this);
        }

        return $this;
    }

    public function removeFamiliare(Familiar $familiare): self
    {
        if ($this->familiares->contains($familiare)) {
            $this->familiares->removeElement($familiare);
            // set the owning side to null (unless already changed)
            if ($familiare->getHv() === $this) {
                $familiare->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Idioma[]
     */
    public function getIdiomas(): Collection
    {
        return $this->idiomas;
    }

    public function addIdioma(Idioma $idioma): self
    {
        if (!$this->idiomas->contains($idioma)) {
            $this->idiomas[] = $idioma;
            $idioma->setHv($this);
        }

        return $this;
    }

    public function removeIdioma(Idioma $idioma): self
    {
        if ($this->idiomas->contains($idioma)) {
            $this->idiomas->removeElement($idioma);
            // set the owning side to null (unless already changed)
            if ($idioma->getHv() === $this) {
                $idioma->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RedSocial[]
     */
    public function getRedesSociales(): Collection
    {
        return $this->redesSociales;
    }

    public function addRedesSociale(RedSocial $redesSociale): self
    {
        if (!$this->redesSociales->contains($redesSociale)) {
            $this->redesSociales[] = $redesSociale;
            $redesSociale->setHv($this);
        }

        return $this;
    }

    public function removeRedesSociale(RedSocial $redesSociale): self
    {
        if ($this->redesSociales->contains($redesSociale)) {
            $this->redesSociales->removeElement($redesSociale);
            // set the owning side to null (unless already changed)
            if ($redesSociale->getHv() === $this) {
                $redesSociale->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Referencia[]
     */
    public function getReferencias(?int $tipo = null): Collection
    {
        if($tipo) {
            $referencias = new ArrayCollection();
            foreach($this->referencias as $referencia) {
                if($referencia->getTipo() === $tipo) {
                    $referencias->add($referencia);
                }
            }
        } else {
            $referencias = $this->referencias;
        }
        return $referencias;
    }

    public function addReferencia(Referencia $referencia): self
    {
        if (!$this->referencias->contains($referencia)) {
            $this->referencias[] = $referencia;
            $referencia->setHv($this);
        }

        return $this;
    }

    public function removeReferencia(Referencia $referencia): self
    {
        if ($this->referencias->contains($referencia)) {
            $this->referencias->removeElement($referencia);
            // set the owning side to null (unless already changed)
            if ($referencia->getHv() === $this) {
                $referencia->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vivienda[]
     */
    public function getViviendas(): Collection
    {
        return $this->viviendas;
    }

    public function addVivienda(Vivienda $vivienda): self
    {
        if (!$this->viviendas->contains($vivienda)) {
            $this->viviendas[] = $vivienda;
            $vivienda->setHv($this);
        }

        return $this;
    }

    public function removeVivienda(Vivienda $vivienda): self
    {
        if ($this->viviendas->contains($vivienda)) {
            $this->viviendas->removeElement($vivienda);
            // set the owning side to null (unless already changed)
            if ($vivienda->getHv() === $this) {
                $vivienda->setHv(null);
            }
        }

        return $this;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(?\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getLmilitarClase(): ?int
    {
        return $this->lmilitarClase;
    }

    public function setLmilitarClase(?int $lmilitarClase): self
    {
        $this->lmilitarClase = $lmilitarClase;

        return $this;
    }

    public function getLmilitarNumero(): ?int
    {
        return $this->lmilitarNumero;
    }

    public function setLmilitarNumero(?int $lmilitarNumero): self
    {
        $this->lmilitarNumero = $lmilitarNumero;

        return $this;
    }

    public function getLmilitarDistrito(): ?int
    {
        return $this->lmilitarDistrito;
    }

    public function setLmilitarDistrito(?int $lmilitarDistrito): self
    {
        $this->lmilitarDistrito = $lmilitarDistrito;

        return $this;
    }

    public function getPresupuestoMensual(): ?int
    {
        return $this->presupuestoMensual;
    }

    public function setPresupuestoMensual(?int $presupuestoMensual): self
    {
        $this->presupuestoMensual = $presupuestoMensual;

        return $this;
    }

    public function getDeudas(): ?bool
    {
        return $this->deudas;
    }

    public function setDeudas(?bool $deudas): self
    {
        $this->deudas = $deudas;

        return $this;
    }

    public function getDeudasConcepto(): ?string
    {
        return $this->deudasConcepto;
    }

    public function setDeudasConcepto(?string $deudasConcepto): self
    {
        $this->deudasConcepto = $deudasConcepto;

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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(?string $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getHv(): ?Hv
    {
        return $this;
    }

    public function getScraper(): ?bool
    {
        return $this->scraper;
    }

    public function setScraper(bool $scraper): self
    {
        $this->scraper = $scraper;

        return $this;
    }
}
