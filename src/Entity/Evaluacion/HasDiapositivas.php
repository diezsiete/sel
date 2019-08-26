<?php


namespace App\Entity\Evaluacion;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class HasDiapositivas
{
    /**
     * @var Diapositiva[]|ArrayCollection
     */
    protected $diapositivas;

    public function __construct()
    {
        $this->diapositivas = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Diapositiva[]
     */
    public function getDiapositivas(): Collection
    {
        return $this->diapositivas;
    }

    public function addDiapositiva(Diapositiva $diapositiva): self
    {
        if (!$this->diapositivas->contains($diapositiva)) {
            $this->diapositivas[] = $diapositiva;
        }

        return $this;
    }

    public function removeDiapositiva(Diapositiva $diapositiva): self
    {
        if ($this->diapositivas->contains($diapositiva)) {
            $this->diapositivas->removeElement($diapositiva);
        }

        return $this;
    }

    public function getNextDiapositiva(Diapositiva $diapositiva)
    {
        $index = $this->diapositivas->indexOf($diapositiva);
        if($index !== false && $index < $this->diapositivas->count() - 1) {
            return $this->diapositivas->get($index + 1);
        }
        return null;
    }

    public function getPrevDiapositiva(Diapositiva $diapositiva)
    {
        $index = $this->diapositivas->indexOf($diapositiva);
        if($index !== false && $index > 0) {
            return $this->diapositivas->get($index - 1);
        }
        return null;
    }

    public function getFirstDiapositiva()
    {
        return $this->diapositivas->first();
    }

    public function getUltimaDiapositiva()
    {
        return $this->diapositivas->last();
    }

    public function isLastDiapositiva(Diapositiva $diapositiva)
    {
        return $this->diapositivas->last() === $diapositiva;
    }

    public function isFirstDiapositiva(Diapositiva $diapositiva)
    {
        return $this->diapositivas->first() === $diapositiva;
    }

    public function hasDiapositivas()
    {
        return $this->diapositivas->count() > 0;
    }
}