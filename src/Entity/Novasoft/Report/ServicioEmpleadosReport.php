<?php


namespace App\Entity\Novasoft\Report;

use App\Entity\Main\Usuario;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * @deprecated
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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $ssrsDb;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $usuario;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSsrsDb()
    {
        return $this->ssrsDb;
    }

    /**
     * @param mixed $ssrsDb
     * @return $this
     */
    public function setSsrsDb($ssrsDb)
    {
        $this->ssrsDb = $ssrsDb;
        return $this;
    }

    /**
     * @return Usuario|null
     */
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
}