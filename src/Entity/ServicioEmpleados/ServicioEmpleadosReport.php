<?php


namespace App\Entity\ServicioEmpleados;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class ServicioEmpleadosReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    protected $source = 'novasoft';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $usuario;

    /**
     * @ORM\Column(type="string", length=27)
     */
    protected $sourceId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function isSourceNovasoft(): bool
    {
        return $this->source === 'novasoft';
    }

    public function isSourceHalcon(): bool
    {
        return $this->source === 'halcon';
    }

    /**
     * @return $this
     */
    public function setSourceNovasoft()
    {
        $this->source = 'novasoft';
        return $this;
    }

    /**
     * @return $this
     */
    public function setSourceHalcon()
    {
        $this->source = 'halcon';
        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * @param Usuario|null $usuario
     * @return $this
     */
    public function setUsuario(?Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getSourceId(): ?string
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceId
     * @return $this
     */
    public function setSourceId(string $sourceId)
    {
        $this->sourceId = $sourceId;

        return $this;
    }
}