<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\ReferenciaRepository")
 */
class Referencia implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese el tipo de referencia")
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=105)
     * @Assert\NotNull(message="Ingrese nombre")
     * @Assert\Length(
     *      max = 105,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=145)
     * @Assert\NotNull(message="Ingrese ocupación")
     * @Assert\Length(
     *      max = 145,
     *      maxMessage = "La ocupación supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $ocupacion;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Assert\NotNull(message="Ingrese parentesco")
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "El parentesco supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $parentesco;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull(message="Ingrese celular de la referencia")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "El celular supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotNull(message="Ingrese telefono de la referencia")
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Solo se aceptan numeros")
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "El telefono supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La dirección supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "scraper", "scraper-hv-child"})
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="referencias")
     * @ORM\JoinColumn(nullable=false)
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


    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    /**
     * @param integer $tipo
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
}
