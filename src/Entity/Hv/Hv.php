<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Main\Usuario;
use App\Entity\Main\Pais;
use App\Entity\Vacante\Vacante;
use App\Service\Utils\Symbol;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={"path"="/hv"}
 *     },
 *     itemOperations={
 *         "get"
 *     },
 *     normalizationContext={"groups"={"api:hv:read"}},
 *     denormalizationContext={"groups"={"api:hv:write"}},
 *     attributes={"validation_groups"={"Default", "api"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Hv\HvRepository")
 */
class Hv implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"messenger:hv-child:put"})
     */
    public $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Main\Usuario", cascade={"persist", "remove"})
     * @Groups({"napi:hv:post", "napi:hv:put", "napi:hv-child:post", "messenger:hv-child:put", "scraper", "scraper-hv", "scraper-hv-child"})
     * @var Usuario
     */
    private $usuario;

    /**
     * Pais de nacimiento
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Ingrese pais de nacimiento")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nacPais;

    /**
     * Departamento de nacimiento
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Dpto")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nacDpto;

    /**
     * Ciudad de nacimiento
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Ciudad")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nacCiudad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Ingrese pais de identificación")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $identPais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Dpto")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $identDpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Ciudad")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $identCiudad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Genero")
     * @ORM\JoinColumn(name="genero", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $genero;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\EstadoCivil")
     * @ORM\JoinColumn(name="estado_civil", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $estadoCivil;

    /**
     * Pais de residencia
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $resiPais;

    /**
     * Departamento de residencia
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Dpto")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $resiDpto;

    /**
     * Ciudad de residencia
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Ciudad")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $resiCiudad;

    /**
     * Barrio donde reside
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Ingrese barrio")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Barrio supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $barrio;

    /**
     * Dirección residencia
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Assert\NotNull(message="Ingrese dirección")
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "La dirección supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\GrupoSanguineo")
     * @ORM\JoinColumn(name="grupo_sanguineo", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $grupoSanguineo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\FactorRh")
     * @ORM\JoinColumn(name="factor_rh", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $factorRh;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Nacionalidad")
     * @ORM\JoinColumn(name="nacionalidad", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Email(message="Ingrese un email valido")
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv"})
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
     * @ORM\ManyToOne(targetEntity="IdentificacionTipo")
     * @ORM\JoinColumn(name="identificacion_tipo", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv"})
     */
    private $identificacionTipo;

    /**
     * Fecha de nacimiento
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank(message="Ingrese fecha de nacimiento")
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lmilitarClase;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\NivelAcademico")
     * @ORM\JoinColumn(name="nivel_academico", referencedColumnName="id", nullable=false)
     * @Groups({"napi:hv:post", "napi:hv:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv"})
     */
    private $nivelAcademico;

    /**
     * @ORM\Column(type="string", length=17, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv"})
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv"})
     */
    private $celular;

    /**
     * @ORM\OneToMany(targetEntity="Estudio", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "api:hv:write", "scraper", "estudios"})
     * @Assert\Count(
     *     min = 1,
     *     minMessage = "Se espera minimo un estudio",
     *     groups={"api"}
     * )
     * @Assert\Valid()
     */
    private $estudios;

    /**
     * @ORM\OneToMany(targetEntity="Experiencia", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "api:hv:write", "scraper", "experiencia"})
     * @Assert\Count(
     *     min = 1,
     *     minMessage = "Se espera minimo una experiencia",
     *     groups={"api"}
     * )
     * @Assert\Valid()
     */
    private $experiencias;

    /**
     * @ORM\OneToMany(targetEntity="Familiar", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "scraper", "familiares"})
     */
    private $familiares;

    /**
     * @ORM\OneToMany(targetEntity="Idioma", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "scraper", "idiomas"})
     */
    private $idiomas;

    /**
     * @ORM\OneToMany(targetEntity="RedSocial", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "scraper", "redesSociales"})
     */
    private $redesSociales;

    /**
     * @ORM\OneToMany(targetEntity="Referencia", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "napi:referencia:post", "api:hv:write", "scraper", "referencias"})
     * @Assert\Count(
     *     min = 3,
     *     minMessage = "Se espera minimo tres referencias",
     *     groups={"api"}
     * )
     * @Assert\Valid()
     */
    private $referencias;

    /**
     * @ORM\OneToMany(targetEntity="Vivienda", mappedBy="hv", orphanRemoval=true)
     * @Groups({"napi:hv:post", "scraper", "viviendas"})
     */
    private $viviendas;

    /**
     * @var Adjunto
     * @ORM\OneToOne(targetEntity="Adjunto", mappedBy="hv", cascade={"persist", "remove"})
     */
    private $adjunto;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante\Vacante", mappedBy="hvs")
     */
    private $vacantes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $t3rs;

    public function __construct()
    {
        $this->estudios = new ArrayCollection();
        $this->experiencias = new ArrayCollection();
        $this->familiares = new ArrayCollection();
        $this->idiomas = new ArrayCollection();
        $this->redesSociales = new ArrayCollection();
        $this->referencias = new ArrayCollection();
        $this->viviendas = new ArrayCollection();
        $this->vacantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Assert\Valid(groups={"api"})
     */
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

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero): self
    {
        $this->genero = $genero;

        return $this;
    }

    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil): self
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

    public function getAdjunto(): ?Adjunto
    {
        return $this->adjunto;
    }

    public function setAdjunto(Adjunto $adjunto): self
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
    public function getExperiencias(): Collection
    {
        return $this->experiencias;
    }

    public function addExperiencia(Experiencia $experiencia): self
    {
        if (!$this->experiencias->contains($experiencia)) {
            $this->experiencias[] = $experiencia;
            $experiencia->setHv($this);
        }

        return $this;
    }

    public function removeExperiencia(Experiencia $experiencia): self
    {
        if ($this->experiencias->contains($experiencia)) {
            $this->experiencias->removeElement($experiencia);
            // set the owning side to null (unless already changed)
            if ($experiencia->getHv() === $this) {
                $experiencia->setHv(null);
            }
        }

        return $this;
    }

    public function getIdentificacionTipo()
    {
        return $this->identificacionTipo;
    }

    public function setIdentificacionTipo($identTipo): self
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

    public function addFamiliar(Familiar $familiar): self
    {
        if (!$this->familiares->contains($familiar)) {
            $this->familiares[] = $familiar;
            $familiar->setHv($this);
        }

        return $this;
    }

    public function removeFamiliar(Familiar $familiar): self
    {
        if ($this->familiares->contains($familiar)) {
            $this->familiares->removeElement($familiar);
            // set the owning side to null (unless already changed)
            if ($familiar->getHv() === $this) {
                $familiar->setHv(null);
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

    public function addRedesSocial(RedSocial $redesSocial): self
    {
        if (!$this->redesSociales->contains($redesSocial)) {
            $this->redesSociales[] = $redesSocial;
            $redesSocial->setHv($this);
        }

        return $this;
    }

    public function removeRedesSocial(RedSocial $redesSocial): self
    {
        if ($this->redesSociales->contains($redesSocial)) {
            $this->redesSociales->removeElement($redesSocial);
            // set the owning side to null (unless already changed)
            if ($redesSocial->getHv() === $this) {
                $redesSocial->setHv(null);
            }
        }

        return $this;
    }

    /**
     * @param int|null $tipo
     * @return Collection|Referencia[]
     */
    public function getReferencias(?int $tipo = null): Collection
    {
        if($tipo) {
            $referencias = new ArrayCollection();
            foreach($this->referencias as $referencia) {
                if($referencia->getTipo()->getId() === $tipo) {
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

    public function getChilds(Symbol $symbol, $child)
    {
        $childClass = $symbol->removeNamespaceFromClassName($child);
        if($childClass === 'RedSocial') {
            $childClass = 'RedesSociales';
        } else {
            $childClass .= 's';
        }
        return $this->{'get' . $childClass}();
    }

    public function getChildsExcept(Symbol $symbol, $child)
    {
        return $this->getChilds($symbol, $child)->filter(function ($sibling) use ($child) {
            return $sibling !== $child;
        });
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

    public function getNivelAcademico()
    {
        return $this->nivelAcademico;
    }

    public function setNivelAcademico($nivelAcademico): self
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


    /**
     * @return Collection|Vacante[]
     */
    public function getVacantes(): Collection
    {
        return $this->vacantes;
    }

    public function addVacante(Vacante $vacante): self
    {
        if (!$this->vacantes->contains($vacante)) {
            $this->vacantes[] = $vacante;
            $vacante->addHv($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeHv($this);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getT3rs(): ?bool
    {
        return $this->t3rs;
    }

    /**
     * @param bool $t3rs
     * @return Hv
     */
    public function isT3rs($t3rs): Hv
    {
        $this->t3rs = $t3rs;
        return $this;
    }

    public function getNapiId(): string
    {
        return $this->usuario->getIdentificacion();
    }

    /**
     * Numero de cedula del aspirante
     * @Assert\NotBlank(message="Por favor ingrese identificación")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      min = 5,
     *      max = 12,
     *      minMessage = "La identificación debe tener al menos {{ limit }} caracteres",
     *      maxMessage = "La identificación supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:hv:read"})
     */
    public function getIdentificacion(): ?string
    {
        return $this->usuario ? $this->usuario->getIdentificacion() : null;
    }

    /**
     * @param string $identificacion
     * @Groups({"api:hv:write"})
     */
    public function setIdentificacion(string $identificacion)
    {
        $this->usuario ? $this->usuario->setIdentificacion($identificacion) : null;
    }

    /**
     * @Assert\NotBlank(message="Por favor ingrese nombre")
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:hv:read"})
     */
    public function getPrimerNombre(): ?string
    {
        return $this->usuario ? $this->usuario->getPrimerNombre() : null;
    }

    /**
     * @param string $primerNombre
     * @Groups({"api:hv:write"})
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->usuario ? $this->usuario->setPrimerNombre($primerNombre) : null;
    }

    /**
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:hv:read"})
     */
    public function getSegundoNombre(): ?string
    {
        return $this->usuario ? $this->usuario->getSegundoNombre() : null;
    }

    /**
     * @param string|null $segundoNombre
     * @Groups({"api:hv:write"})
     */
    public function setSegundoNombre(?string $segundoNombre)
    {
        $this->usuario ? $this->usuario->setSegundoNombre($segundoNombre) : null;
    }

    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido")
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:hv:read"})
     */
    public function getPrimerApellido(): ?string
    {
        return $this->usuario ? $this->usuario->getPrimerApellido() : null;
    }

    /**
     * @param string $primerApellido
     * @Groups({"api:hv:write"})
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->usuario ? $this->usuario->setPrimerApellido($primerApellido) : null;
    }

    /**
     * @Assert\Regex(
     *     pattern="/\d|(?! )\W/",
     *     match=false,
     *     message="Por favor ingrese solo letras"
     * )
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El apellido supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:hv:read"})
     */
    public function getSegundoApellido(): ?string
    {
        return $this->usuario ? $this->usuario->getSegundoApellido() : null;
    }

    /**
     * @param string|null $segundoApellido
     * @Groups({"api:hv:write"})
     */
    public function setSegundoApellido(?string $segundoApellido)
    {
        $this->usuario ? $this->usuario->setSegundoApellido($segundoApellido) : null;
    }
    /**
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email()
     * @Groups({"api:hv:read"})
     */
    public function getEmail(): ?string
    {
        return $this->usuario ? $this->usuario->getEmail() : null;
    }

    /**
     * @param string $email
     * @Groups({"api:hv:write"})
     */
    public function setEmail($email)
    {
        $this->usuario ? $this->usuario->setEmail($email) : null;
    }
}
