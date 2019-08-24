<?php

namespace App\Entity\Evaluacion;

use App\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\PresentacionRepository")
 * @ORM\Table(name="evaluacion_presentacion")
 */
class Presentacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Diapositiva", mappedBy="presentacion", orphanRemoval=true)
     */
    private $diapositivas;

    public function __construct()
    {
        $this->diapositivas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Diapositiva[]
     */
    public function getDiapositivas(): Collection
    {
        return $this->diapositivas;
    }

    public function addDiapositiva(Diapositiva $diapositiva): self
    {
        if (!$this->diapositivas->contains($diapositiva)) {
            $this->diapositivas[] = $diapositiva;
            $diapositiva->setPresentacion($this);
        }

        return $this;
    }

    public function removeDiapositiva(Diapositiva $diapositiva): self
    {
        if ($this->diapositivas->contains($diapositiva)) {
            $this->diapositivas->removeElement($diapositiva);
            // set the owning side to null (unless already changed)
            if ($diapositiva->getPresentacion() === $this) {
                $diapositiva->setPresentacion(null);
            }
        }

        return $this;
    }
}
