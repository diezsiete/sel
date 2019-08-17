<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RepresentanteRepository")
 */
class Representante
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Convenio", inversedBy="representantes")
     * @ORM\JoinColumn(name="convenio_codigo", referencedColumnName="codigo", nullable=false)
     */
    private $convenio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $encargado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bcc;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Empleado", mappedBy="representante")
     */
    private $empleados;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConvenio(): ?Convenio
    {
        return $this->convenio;
    }

    public function setConvenio(?Convenio $convenio): self
    {
        $this->convenio = $convenio;

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

    public function getEncargado(): ?bool
    {
        return $this->encargado;
    }

    public function setEncargado(bool $encargado): self
    {
        $this->encargado = $encargado;

        return $this;
    }

    public function getBcc(): ?bool
    {
        return $this->bcc;
    }

    public function setBcc(bool $bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return Collection|Empleado[]
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function addEmpleado(Empleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados[] = $empleado;
            $empleado->setRepresentante($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): self
    {
        if ($this->empleados->contains($empleado)) {
            $this->empleados->removeElement($empleado);
            // set the owning side to null (unless already changed)
            if ($empleado->getRepresentante() === $this) {
                $empleado->setRepresentante(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}