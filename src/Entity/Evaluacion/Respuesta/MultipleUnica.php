<?php


namespace App\Entity\Evaluacion\Respuesta;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleUnica
 * @package App\Entity\Evaluacion\Respuesta
 * @ORM\Entity
 */
class MultipleUnica extends Respuesta
{
    /**
     * @var Opcion[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluacion\Respuesta\Opcion", mappedBy="respuesta", orphanRemoval=true, cascade={"persist"})
     * @Assert\Count(
     *      min = 1,
     *      max = 1,
     *      exactMessage = "Seleccione una opcion",
     * )
     */
    private $opciones;

    public function __construct()
    {
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
            $opcion->setRespuesta($this);
        }

        return $this;
    }

    public function removeOpcion(Opcion $opcion): self
    {
        if ($this->opciones->contains($opcion)) {
            $this->opciones->removeElement($opcion);
            // set the owning side to null (unless already changed)
            if ($opcion->getRespuesta() === $this) {
                $opcion->setRespuesta(null);
            }
        }

        return $this;
    }

    /**
     * @return MultipleUnica
     */
    public function getPregunta()
    {
        return parent::getPregunta();
    }

    public function evaluar(): bool
    {
        $ok = false;
        foreach($this->opciones as $opcion) {
            $ok = (bool)$opcion->getPreguntaOpcion()->getRespuesta();
        }
        return $ok;
    }

    public function __clone()
    {
        $opciones = new ArrayCollection();
        foreach($this->opciones as $opcion) {
            $opciones->add(clone $opcion);
        }
        $this->opciones = $opciones;
    }
}