<?php

namespace App\Entity\Main;

use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\RepresentanteRepository")
 */
class Representante
{
    const TYPE_CLIENTE = "CLIENTE";
    const TYPE_SERVICIO = "SERVICIO";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Convenio", inversedBy="representantes")
     * @ORM\JoinColumn(name="convenio_codigo", referencedColumnName="codigo", nullable=false)
     */
    private $convenio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
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
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Empleado", mappedBy="representante")
     */
    private $empleados;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     * @Assert\NotBlank(message="Por favor ingrese correo")
     * @Assert\Email()
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

    public function isEncargado(): ?bool
    {
        return $this->encargado;
    }

    public function setEncargado(bool $encargado): self
    {
        $this->encargado = $encargado;
        $this->bcc = !$this->encargado;
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

    /**
     * @param $type
     * @return bool
     */
    public function isType($type)
    {
        return $this->getUsuario()->esRol('ROLE_REPRESENTANTE_' . $type);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        foreach([static::TYPE_SERVICIO, static::TYPE_CLIENTE] as $type) {
            if($this->isType($type)) {
                return $type;
            }
        }
        return null;
    }
}
