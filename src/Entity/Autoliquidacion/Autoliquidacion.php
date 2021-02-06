<?php

namespace App\Entity\Autoliquidacion;

use App\Entity\Main\Convenio;
use App\Entity\Main\Usuario;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Autoliquidacion\AutoliquidacionRepository")
 */
class Autoliquidacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Convenio", inversedBy="autoliquidaciones", )
     * @ORM\JoinColumn(name="convenio_codigo", referencedColumnName="codigo", nullable=true)
     * @Groups("selr:migrate")
     */
    private $convenio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("selr:migrate")
     */
    private $usuario;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     * @Groups("selr:migrate")
     */
    private $periodo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("selr:migrate")
     */
    private $fechaEjecucion;

    /**
     * @ORM\Column(type="smallint")
     * @Groups("selr:migrate")
     */
    private $porcentajeEjecucion = 0;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("selr:migrate")
     */
    private $emailSended = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Autoliquidacion\AutoliquidacionEmpleado", mappedBy="autoliquidacion", orphanRemoval=true)
     */
    private $empleados;

    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     * @Groups("selr:migrate")
     */
    private $emailFailMessage;

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

    public function getPeriodo(): ?\DateTimeInterface
    {
        return $this->periodo;
    }

    public function getPeriodoFormat($format = "Y-m")
    {
        return $this->periodo ? $this->periodo->format($format) : $this->periodo;
    }

    public function setPeriodo(\DateTimeInterface $periodo): self
    {
        $this->periodo = $periodo;

        return $this;
    }

    public function getFechaEjecucion(): ?\DateTimeInterface
    {
        return $this->fechaEjecucion;
    }

    public function setFechaEjecucion(\DateTimeInterface $fechaEjecucion): self
    {
        $this->fechaEjecucion = $fechaEjecucion;

        return $this;
    }

    public function getPorcentajeEjecucion(): ?int
    {
        return $this->porcentajeEjecucion;
    }

    public function setPorcentajeEjecucion(int $porcentajeEjecucion): self
    {
        $this->porcentajeEjecucion = $porcentajeEjecucion;

        return $this;
    }

    public function isEmailSended(): ?bool
    {
        return $this->emailSended;
    }

    public function setEmailSended(bool $emailSended): self
    {
        $this->emailSended = $emailSended;

        return $this;
    }

    /**
     * @return Collection|AutoliquidacionEmpleado[]
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function addEmpleado(AutoliquidacionEmpleado $empleado): self
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados[] = $empleado;
            $empleado->setAutoliquidacion($this);
        }

        return $this;
    }

    public function removeEmpleado(AutoliquidacionEmpleado $empleado): self
    {
        if ($this->empleados->contains($empleado)) {
            $this->empleados->removeElement($empleado);
            // set the owning side to null (unless already changed)
            if ($empleado->getAutoliquidacion() === $this) {
                $empleado->setAutoliquidacion(null);
            }
        }

        return $this;
    }

    public function getEmailFailMessage(): ?string
    {
        return $this->emailFailMessage;
    }

    public function setEmailFailMessage(?string $emailFailMessage): self
    {
        $this->emailFailMessage = $emailFailMessage;

        return $this;
    }

    public function calcularPorcentajeEjecucion()
    {
        $count = $this->empleados->count();
        $exito = 0;

        foreach($this->empleados as $autoliquidacionEmpleado){
            if($autoliquidacionEmpleado->isExito()){
                $exito++;
            }
        }

        $porcentajeEjecucion = $count ? $exito * 100 / $count : 0;

        $porcentajeEjecucion = floor($porcentajeEjecucion);
        $this->porcentajeEjecucion = (int)$porcentajeEjecucion;
        return $this;
    }
}
