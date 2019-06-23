<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity(
 *     fields={"identificacion"},
 *     message="Identificación ya registrada"
 * )
 */
class Usuario implements UserInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Por favor ingrese identificación")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $identificacion;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Por favor ingrese contraseña")
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email()
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su nombre")
     * @ORM\Column(type="string", length=60)
     */
    private $primerNombre;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $segundoNombre;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido")
     * @ORM\Column(type="string", length=60)
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $segundoApellido;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo = true;

    /**
     * @ORM\Column(type="datetime")
     */
    private $aceptoTerminosEn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ultimoLogin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type = 2;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vacante", mappedBy="aplicantes")
     */
    private $vacantes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOld;

    public function __construct()
    {
        $this->vacantes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param string $identificacion
     * @return Usuario
     */
    public function setIdentificacion($identificacion): self
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->identificacion;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string|string[] $rolName
     * @return $this
     */
    public function addRol($rolName)
    {
        $newRoles = is_array($rolName) ? $rolName : [$rolName];
        foreach ($newRoles as $newRole) {
            if (!in_array($newRole, $this->roles)) {
                $this->roles[] = $newRole;
            }
        }
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrimerNombre(): ?string
    {
        return $this->primerNombre;
    }

    /**
     * @param string $primerNombre
     * @return Usuario
     */
    public function setPrimerNombre($primerNombre): self
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    public function getSegundoNombre(): ?string
    {
        return $this->segundoNombre;
    }

    public function setSegundoNombre(?string $segundoNombre): self
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    public function getPrimerApellido(): ?string
    {
        return $this->primerApellido;
    }

    /**
     * @param string $primerApellido
     * @return Usuario
     */
    public function setPrimerApellido($primerApellido): self
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido;
    }

    public function setSegundoApellido(?string $segundoApellido): self
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getAceptoTerminosEn(): ?\DateTimeInterface
    {
        return $this->aceptoTerminosEn;
    }

    /**
     * @param \DateTimeInterface $aceptoTerminosEn
     * @return Usuario
     */
    public function setAceptoTerminosEn(?\DateTimeInterface $aceptoTerminosEn)
    {
        $this->aceptoTerminosEn = $aceptoTerminosEn;
        return $this;
    }


    public function aceptarTerminos(): self
    {
        $this->aceptoTerminosEn = new \DateTime();

        return $this;
    }

    public function getUltimoLogin(): ?\DateTimeInterface
    {
        return $this->ultimoLogin;
    }

    public function setUltimoLogin(?\DateTimeInterface $ultimoLogin): self
    {
        $this->ultimoLogin = $ultimoLogin;

        return $this;
    }

    public function getNombreCompleto($solo_primeros = false, $ucfirst = false)
    {
        $primer_nombre = $ucfirst ? ucfirst(mb_strtolower($this->primerNombre)) : $this->primerNombre;
        $segundo_nombre = $ucfirst ? ucfirst(mb_strtolower($this->segundoNombre)) : $this->segundoNombre;
        $primer_apellido = $ucfirst ? ucfirst(mb_strtolower($this->primerApellido)) : $this->primerApellido;
        $segundo_apellido = $ucfirst ? ucfirst(mb_strtolower($this->segundoApellido)) : $this->segundoApellido;

        if($solo_primeros)
            $return =  $primer_nombre.' '.$primer_apellido;
        else
            $return =  $primer_nombre.' '
                .($segundo_nombre? $segundo_nombre. ' ' : '')
                .$primer_apellido
                .($segundo_apellido ? ' ' . $segundo_apellido : '');
        return $return;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

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
            $vacante->addAplicante($this);
        }

        return $this;
    }

    public function removeVacante(Vacante $vacante): self
    {
        if ($this->vacantes->contains($vacante)) {
            $this->vacantes->removeElement($vacante);
            $vacante->removeAplicante($this);
        }

        return $this;
    }

    public function getIdOld(): ?int
    {
        return $this->idOld;
    }

    public function setIdOld(int $idOld): self
    {
        $this->idOld = $idOld;

        return $this;
    }
}
