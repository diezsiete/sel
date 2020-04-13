<?php
namespace App\Entity\Napi\Report\ServicioEmpleados;

use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @NapiResource(
 *     collectionOperations={
 *         "get"={"path"="/se/nomina?identificacion={identificacion}&fechaInicial={fechaInicial}&fechaFinal={fechaFinal}"},
 *     },
 *     itemOperations={
 *         "get"={
 *             "path"="/se/nomina/identificacion={identificacion};fechaInicial={fechaInicial};fechaFinal={fechaFinal}/pdf"
 *         }
 *     },
 * )
 * @ORM\Entity()
 * @ORM\Table(name="napi_se_nomina")
 */
class Nomina
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @NapiResourceId
     */
    protected $identificacion;
    
    /**
     * @ORM\Column(type="date")
     * @NapiResourceId
     */
    protected $fechaInicial;


    /**
     * @ORM\Column(type="date")
     * @NapiResourceId
     */
    protected $fechaFinal;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $nombreConvenio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Usuario")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $usuario;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     * @return Nomina
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     * @return Nomina
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaInicial()
    {
        return $this->fechaInicial;
    }

    /**
     * @param mixed $fechaInicial
     * @return Nomina
     */
    public function setFechaInicial($fechaInicial)
    {
        $this->fechaInicial = $fechaInicial;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaFinal()
    {
        return $this->fechaFinal;
    }

    /**
     * @param mixed $fechaFinal
     * @return Nomina
     */
    public function setFechaFinal($fechaFinal)
    {
        $this->fechaFinal = $fechaFinal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombreConvenio()
    {
        return $this->nombreConvenio;
    }

    /**
     * @param mixed $nombreConvenio
     * @return Nomina
     */
    public function setNombreConvenio($nombreConvenio)
    {
        $this->nombreConvenio = $nombreConvenio;
        return $this;
    }
}