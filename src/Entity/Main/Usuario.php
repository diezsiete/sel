<?php

namespace App\Entity\Main;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\Serializer\NormalizeFunction;
use App\Helper\Novasoft\Api\NapiAwareChangeEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={
 *         "get"={"path"="/usuario/{id}"},
 *         "one-by"={
 *             "method"="GET",
 *             "path"="/usuario/one-by/{field}/{id}"
 *         }
 *     },
 *     normalizationContext={"groups"={"api"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Main\UsuarioRepository")
 * @UniqueEntity(
 *     fields={"identificacion"},
 *     message="Identificación ya registrada",
 *     groups={"Default", "api"}
 * )
 * @ApiFilter(SearchFilter::class, properties={"identificacion": "exact"})
 */
class Usuario implements UserInterface
{
    use TimestampableEntity;
    use NapiAwareChangeEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api", "messenger:hv-child:put"})
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Por favor ingrese identificación", groups={"Default", "api"})
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"napi:hv:post", "napi:hv-child:post", "messenger:hv-child:put", "scraper", "scraper-hv", "scraper-hv-child", "api", "selr:migrate"})
     */
    private $identificacion;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\NotBlank(message="Por favor ingrese contraseña", groups={"Default"})
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Por favor ingrese correo", groups={"Default", "api"})
     * @Assert\Email(groups={"Default", "api"})
     * @ORM\Column(type="string", length=140, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv", "api"})
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su nombre", groups={"Default", "api"})
     * @ORM\Column(type="string", length=60)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv", "api"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:hv:put"})
     */
    private $primerNombre;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv", "api"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:hv:put"})
     */
    private $segundoNombre;

    /**
     * @Assert\NotBlank(message="Por favor ingrese su apellido", groups={"Default", "api"})
     * @ORM\Column(type="string", length=60)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv", "api"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:hv:put"})
     */
    private $primerApellido;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"napi:hv:post", "napi:hv:put", "scraper", "scraper-hv", "api"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:hv:put"})
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idOld;


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
            if (!in_array($newRole, $this->roles, true)) {
                $this->roles[] = $newRole;
            }
        }
        return $this;
    }

    /**
     * @param array|string $rol_nombre
     * @return bool
     */
    public function esRol($rol_nombre)
    {
        $rol_nombre = is_array($rol_nombre) ? $rol_nombre : [$rol_nombre];
        $es_rol = false;
        if(!$rol_nombre)
            $es_rol = true;
        else {
            foreach ($rol_nombre as $rol) {
                if (strpos($rol, '/') !== false) {
                    $es_rol = count(preg_grep($rol, $this->roles)) > 0;
                } else if (in_array($rol, $this->roles)) {
                    $es_rol = true;
                }
                if($es_rol) {
                    break;
                }
            }
        }
        return $es_rol;
    }

    public function removeRol($rolNombre)
    {
        if (($key = array_search($rolNombre, $this->roles)) !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
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
        return $this->set('email', $email);
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
        return $this->set('primerNombre', $primerNombre);
    }

    public function getSegundoNombre(): ?string
    {
        return $this->segundoNombre ?? '';
    }

    public function setSegundoNombre(?string $segundoNombre): self
    {
        return $this->set('segundoNombre', $segundoNombre);
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
        return $this->set('primerApellido', $primerApellido);
    }

    public function getSegundoApellido(): ?string
    {
        return $this->segundoApellido ?? '';
    }

    public function setSegundoApellido(?string $segundoApellido): self
    {
        return $this->set('segundoApellido', $segundoApellido);
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

    /**
     * @param bool $solo_primeros
     * @param bool $ucfirst
     * @return string
     * @Groups({"api"})
     */
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

    /**
     * @return string
     * @Groups({"api"})
     */
    public function getNombrePrimeros()
    {
        return $this->getNombreCompleto(true, true);
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
