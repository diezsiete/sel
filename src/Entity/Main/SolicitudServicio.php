<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\SolicitudServicioRepository")
 */
class SolicitudServicio
{
    const SERVICIO_TYPE = [
        1 => 'Suministro Personal Temporal',
        2 => 'Procesos de Selección',
        3 => 'Payroll - Tercerización Nómina',
        4 => 'Tercerización procesos'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     * @Assert\NotNull(message="Ingrese el nombre de su empresa")
     * @var string
     */
    private $nombreEmpresa;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="smallint")
     */
    private $servicio = 1;

    /**
     * @ORM\Column(type="string", length=140)
     * @Assert\NotNull(message="Ingrese su nombre completo")
     */
    private $nombreContacto;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotNull(message="Ingrese su email corporativo")
     * @Assert\Email()
     */
    private $emailCorporativo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\NotNull(message="Ingrese el telefono donde podremos contactarlo")
     */
    private $telefonoContacto;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\NotBlank(message="Ingrese ciudad")
     * @Assert\Length(max=120, maxMessage="Campo supera limite de {{ limit }} caracteres")
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Assert\NotBlank(message="Ingrese cargo")
     * @Assert\Length(max=120, maxMessage="Campo supera limite de {{ limit }} caracteres")
     */
    private $cargo;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * @param string $nombreEmpresa
     * @return SolicitudServicio
     */
    public function setNombreEmpresa(string $nombreEmpresa): SolicitudServicio
    {
        $this->nombreEmpresa = $nombreEmpresa;
        return $this;
    }


    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getServicio()
    {
        return $this->servicio;
    }

    public function setServicio($servicio): self
    {
        $this->servicio = $servicio;

        return $this;
    }

    public function getNombreContacto(): ?string
    {
        return $this->nombreContacto;
    }

    public function setNombreContacto(string $nombreContacto): self
    {
        $this->nombreContacto = $nombreContacto;

        return $this;
    }

    public function getEmailCorporativo(): ?string
    {
        return $this->emailCorporativo;
    }

    public function setEmailCorporativo(string $emailCorporativo): self
    {
        $this->emailCorporativo = $emailCorporativo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * @param mixed $telefonoContacto
     * @return SolicitudServicio
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;
        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(?string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(?string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }
}
