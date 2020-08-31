<?php

namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={"post"},
 *     itemOperations={"get", "put", "delete"},
 *     normalizationContext={"groups"={"api:hv:read"}},
 *     denormalizationContext={"groups"={"api:hv:write"}},
 *     attributes={"validation_groups"={"Default", "api"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"hv": "exact"})
 * @ORM\Entity(repositoryClass="App\Repository\Hv\EstudioRepository")
 * @HvChildConstraint(
 *     uniqueFields={"codigo", "instituto"},
 *     message="No puede tener dos estudios con la misma area en el mismo instituto"
 * )
 */
class Estudio implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"main", "api:hv:read", "api:cv:read"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="EstudioCodigo")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione el area de estudio")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "messenger:hv-child:put",  "api:hv:write", "api:hv:read", "scraper", "scraper-hv-child", "api:cv:read", "api:cv:write"})
     * @var EstudioCodigo
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="Ingrese nombre del estudio")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El titulo supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "api:hv:write", "api:hv:read", "scraper", "scraper-hv-child", "api:cv:read", "api:cv:write"})
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="EstudioInstituto")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione instituto. Si no lo encuentra seleccione opción 'NO APLICA'")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "messenger:hv-child:put", "api:hv:write", "scraper", "scraper-hv-child"})
     * @var EstudioInstituto
     */
    private $instituto;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $fin;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     * @Assert\Length(
     *      max = 75,
     *      maxMessage = "El nombre supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"api:cv:read", "api:cv:write"})
     */
    private $institutoNombreAlt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"api:cv:read", "api:cv:write"})
     */
    private $anoEstudio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"api:cv:read", "api:cv:write"})
     */
    private $horasEstudio;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child", "api:cv:read", "api:cv:write"})
     */
    private $graduado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"api:cv:read", "api:cv:write"})
     */
    private $semestresAprobados;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child", "api:cv:read", "api:cv:write"})
     */
    private $cancelo = 0;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     * @Groups({"api:cv:read", "api:cv:write"})
     */
    private $numeroTarjeta;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="estudios")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api:cv:write"})
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

    public function getCodigo(): ?EstudioCodigo
    {
        return $this->codigo;
    }

    public function setCodigo(?EstudioCodigo $codigo): self
    {
        $this->codigo = $codigo;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return Estudio
     */
    public function setNombre($nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getInstituto(): ?EstudioInstituto
    {
        return $this->instituto;
    }

    public function setInstituto(?EstudioInstituto $instituto): self
    {
        $this->instituto = $instituto;
        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getInstitutoNombreAlt(): ?string
    {
        return $this->institutoNombreAlt;
    }

    public function setInstitutoNombreAlt(?string $institutoNombreAlt): self
    {
        $this->institutoNombreAlt = $institutoNombreAlt;

        return $this;
    }

    public function getAnoEstudio(): ?int
    {
        return $this->anoEstudio;
    }

    public function setAnoEstudio(?int $anoEstudio): self
    {
        $this->anoEstudio = $anoEstudio;

        return $this;
    }

    public function getHorasEstudio(): ?int
    {
        return $this->horasEstudio;
    }

    public function setHorasEstudio(?int $horasEstudio): self
    {
        $this->horasEstudio = $horasEstudio;

        return $this;
    }

    public function getGraduado(): ?bool
    {
        return $this->graduado;
    }

    public function setGraduado(?bool $graduado): self
    {
        $this->graduado = $graduado;

        return $this;
    }

    public function getSemestresAprobados(): ?int
    {
        return $this->semestresAprobados;
    }

    public function setSemestresAprobados(?int $semestresAprobados): self
    {
        $this->semestresAprobados = $semestresAprobados;

        return $this;
    }

    public function getCancelo(): ?bool
    {
        return $this->cancelo;
    }

    public function setCancelo(?bool $cancelo): self
    {
        $this->cancelo = $cancelo;

        return $this;
    }

    public function getNumeroTarjeta(): ?string
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(?string $numeroTarjeta): self
    {
        $this->numeroTarjeta = $numeroTarjeta;

        return $this;
    }

    public function getNapiId(): string
    {
        $codigo = $this->codigo;
        $instituto = $this->instituto;
        return "hv={$this->hv->getNapiId()};codigo={$codigo->getId()};instituto={$instituto->getId()}";
    }
}
