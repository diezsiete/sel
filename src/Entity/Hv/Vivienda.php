<?php

namespace App\Entity\Hv;

use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Main\Pais;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\ViviendaRepository")
 * @HvChildConstraint(
 *     message="No puede tener viviendas con dirección repetida",
 *     uniqueFields={"strtolower(direccion)"}
 * )
 */
class Vivienda implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\NotNull(message="Ingrese dirección")
     * @Assert\Length(
     *      max = 40,
     *      maxMessage = "La dirección supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "scraper", "scraper-hv-child"})
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Pais")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione pais donde se ubica la vivienda")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $pais;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Dpto")
     * @Assert\NotNull(message="Seleccione departamento donde se ubica la vivienda")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $dpto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Ciudad")
     * @Assert\NotNull(message="Seleccione ciudad donde se ubica la vivienda")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $ciudad;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $estrato;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Seleccione tipo de vivienda")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $tipoVivienda = 1;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull(message="Ingrese valor")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $tenedor = 1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $viviendaActual;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Hv\Hv", inversedBy="viviendas")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("napi:hv-child:post")
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }

    public function getDpto(): ?Dpto
    {
        return $this->dpto;
    }

    public function setDpto(?Dpto $dpto): self
    {
        $this->dpto = $dpto;

        return $this;
    }

    public function getCiudad(): ?Ciudad
    {
        return $this->ciudad;
    }

    public function setCiudad(?Ciudad $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getEstrato(): ?int
    {
        return $this->estrato;
    }

    public function setEstrato(?int $estrato): self
    {
        $this->estrato = $estrato;

        return $this;
    }

    public function getTipoVivienda(): ?int
    {
        return $this->tipoVivienda;
    }

    public function setTipoVivienda(?int $tipoVivienda): self
    {
        $this->tipoVivienda = $tipoVivienda;

        return $this;
    }

    public function getTenedor(): ?int
    {
        return $this->tenedor;
    }

    public function setTenedor(?int $tenedor): self
    {
        $this->tenedor = $tenedor;

        return $this;
    }

    public function getViviendaActual(): ?bool
    {
        return $this->viviendaActual;
    }

    public function setViviendaActual(?bool $viviendaActual): self
    {
        $this->viviendaActual = $viviendaActual;

        return $this;
    }

    public function getNapiId(): string
    {
        // TODO: Implement getNapiId() method.
    }
}
