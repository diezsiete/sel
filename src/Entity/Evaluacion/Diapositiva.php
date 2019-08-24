<?php

namespace App\Entity\Evaluacion;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Evaluacion\DiapositivaRepository")
 * @ORM\Table(name="evaluacion_diapositiva")
 */
class Diapositiva
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evaluacion\Presentacion", inversedBy="diapositivas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $presentacion;

    /**
     * @ORM\Column(type="smallint")
     */
    private $indice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @Gedmo\Slug(fields={"nombre"})
     * @ORM\Column(type="string", length=100)
     */
    private $slug;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPresentacion(): ?Presentacion
    {
        return $this->presentacion;
    }

    public function setPresentacion(?Presentacion $presentacion): self
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(int $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Diapositiva
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }


}
