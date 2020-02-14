<?php

namespace App\Entity\Hv;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Hv\HvChild as HvChildConstraint;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Hv\EstudioRepository")
 * @HvChildConstraint(
 *     message="No puede tener dos estudios con la misma area en el mismo instituto",
 *     uniqueFields={"codigo", "instituto"}
 * )
 */
class Estudio implements HvEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="EstudioCodigo")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione el area de estudio")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     * @var EstudioCodigo
     */
    private $codigo;

    /**
     * @var EstudioCodigo
     * @Groups("messenger:hv-child:put")
     */
    private $codigoPrev;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="Ingrese nombre del estudio")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "El titulo supera el limite de {{ limit }} caracteres"
     * )
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="EstudioInstituto")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Seleccione instituto. Si no lo encuentra seleccione opción 'NO APLICA'")
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "scraper", "scraper-hv-child"})
     * @var EstudioInstituto
     */
    private $instituto;

    /**
     * @var EstudioInstituto
     * @Groups("messenger:hv-child:put")
     */
    private $institutoPrev;

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
     * @Groups("main")
     */
    private $institutoNombreAlt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anoEstudio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $horasEstudio;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $graduado;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $semestresAprobados;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"main", "napi:hv:post", "napi:hv-child:post", "napi:hv-child:put", "scraper", "scraper-hv-child"})
     */
    private $cancelo = 0;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $numeroTarjeta;

    /**
     * @ORM\ManyToOne(targetEntity="Hv", inversedBy="estudios")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"napi:hv-child:post", "napi:hv-child:put"})
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
        $this->codigoPrev = $this->codigo;
        $this->codigo = $codigo;
        return $this;
    }

    public function getCodigoPrev(): ?EstudioCodigo
    {
        return $this->codigoPrev;
    }

    public function setCodigoPrev(EstudioCodigo $codigoPrev): Estudio
    {
        $this->codigoPrev = $codigoPrev;
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
        $this->institutoPrev = $this->instituto;
        $this->instituto = $instituto;
        return $this;
    }


    public function getInstitutoPrev(): ?EstudioInstituto
    {
        return $this->institutoPrev;
    }

    public function setInstitutoPrev(EstudioInstituto $institutoPrev): Estudio
    {
        $this->institutoPrev = $institutoPrev;
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
        $codigo = $this->codigoPrev ?? $this->codigo;
        $instituto = $this->institutoPrev ?? $this->instituto;
        return "hv={$this->hv->getNapiId()};codigo={$codigo->getId()};instituto={$instituto->getId()}";
    }
}
