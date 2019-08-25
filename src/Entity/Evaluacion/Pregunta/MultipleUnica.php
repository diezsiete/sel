<?php


namespace App\Entity\Evaluacion\Pregunta;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleUnica
 * @package App\Entity\Evaluacion\Pregunta
 * @ORM\Entity
 */
class MultipleUnica extends Pregunta
{
    /**
     * @var Collection|Opcion[]
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Pregunta\Opcion", mappedBy="pregunta", orphanRemoval=true, cascade={"persist"})
     * @Assert\Count(
     *      min = 1,
     *      max = 1,
     *      exactMessage = "Seleccione perro",
     * )
     */
    protected $opciones;

    public function __construct()
    {
        parent::__construct();
        $this->opciones = new ArrayCollection();
    }

    /**
     * @return Collection|Opcion[]
     */
    public function getOpciones(): Collection
    {
        return $this->opciones;
    }

    public function addOpcion(Opcion $opcion): self
    {
        if (!$this->opciones->contains($opcion)) {
            $this->opciones[] = $opcion;
            $opcion->setPregunta($this);
        }

        return $this;
    }

    public function removeOpcion(Opcion $opcion): self
    {
        if ($this->opciones->contains($opcion)) {
            $this->opciones->removeElement($opcion);
            // set the owning side to null (unless already changed)
            if ($opcion->getPregunta() === $this) {
                $opcion->setPregunta(null);
            }
        }

        return $this;
    }
}