<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Annotation\Serializer\NormalizeFunction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"={"path"="/referencias"},
 *         "post"={"path"="/referencia"}
 *     },
 *     itemOperations={
 *         "get"={"path"="/referencia/{id}"},
 *         "put"={"path"="/referencia/{id}"},
 *         "delete"={"path"="/referencia/{id}"}
 *     },
 *     normalizationContext={"groups"={"api:cv:read"}},
 *     denormalizationContext={"groups"={"api:cv:write"}},
 *     attributes={"validation_groups"={"Default", "api"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Hv\ReferenciaRepository")
 */
class Referencia implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api:cv:read"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\ReferenciaTipo")
     * @ORM\JoinColumn(name="tipo", referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Ingrese el tipo de referencia")
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "La ocupación supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El parentesco supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $parentesco;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="Ingrese celular de la referencia")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El celular supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotNull(message="Ingrese telefono de la referencia")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El telefono supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La dirección supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     * @NormalizeFunction("strtoupper", groups={"napi:hv:post", "napi:referencia:post", "napi:hv-child:put"})
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(
     *      max = 200,
     *      maxMessage = "Nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:referencia:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="referencias")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"napi:hv-child:post", "messenger:hv-child:put"})
     * @var Hv
     */
    protected $hv;

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

    /**
     * @return ReferenciaTipo|null
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param ReferenciaTipo $tipo
     * @return Referencia
     */
    public function setTipo($tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return Referencia
     */
    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    /**
     * @param string $ocupacion
     * @return Referencia
     */
    public function setOcupacion($ocupacion): self
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getParentesco(): ?string
    {
        return $this->parentesco;
    }

    public function setParentesco(?string $parentesco): self
    {
        $this->parentesco = $parentesco;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     * @return Referencia
     */
    public function setCelular($celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getEntidad(): ?string
    {
        return $this->entidad;
    }

    public function setEntidad(?string $entidad): self
    {
        $this->entidad = $entidad;

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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getNapiId(): string
    {
        return "hv={$this->hv->getNapiId()};tipo={$this->tipo}";
    }
}
