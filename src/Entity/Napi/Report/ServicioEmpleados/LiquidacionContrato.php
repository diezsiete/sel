<?php


namespace App\Entity\Napi\Report\ServicioEmpleados;

use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @NapiResource(
 *     collectionOperations={
 *         "get"={"path"="/se/liquidacion-contrato?identificacion={identificacion}&fechaIngreso={fechaIngreso}&fechaRetiro={fechaRetiro}"},
 *     },
 *     itemOperations={
 *         "get"={
 *             "path"="/se/liquidacion-contrato/identificacion={identificacion};fechaIngreso={fechaIngreso};fechaRetiro={fechaCorte}/pdf"
 *         },
 *     }
 * )
 * @ORM\Entity()
 * @ORM\Table(name="napi_se_liquidacion_contrato")
 */
class LiquidacionContrato
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=12)
     * @NapiResourceId
     */
    protected $identificacion;

    /**
     * @ORM\Column(type="date")
     * @NapiResourceId
     */
    protected $fechaIngreso;


    /**
     * @ORM\Column(type="date")
     */
    protected $fechaRetiro;

    /**
     * @ORM\Column(type="integer")
     */
    protected $contrato;

    /**
     * @ORM\Column(type="date")
     * @NapiResourceId
     */
    protected $fechaCorte;

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
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * @param mixed $identificacion
     * @return LiquidacionContrato
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * @param mixed $fechaIngreso
     * @return LiquidacionContrato
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaRetiro()
    {
        return $this->fechaRetiro;
    }

    /**
     * @param mixed $fechaRetiro
     * @return LiquidacionContrato
     */
    public function setFechaRetiro($fechaRetiro)
    {
        $this->fechaRetiro = $fechaRetiro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * @param mixed $contrato
     * @return LiquidacionContrato
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFechaCorte()
    {
        return $this->fechaCorte;
    }

    /**
     * @param mixed $fechaCorte
     * @return LiquidacionContrato
     */
    public function setFechaCorte($fechaCorte)
    {
        $this->fechaCorte = $fechaCorte;
        return $this;
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
     * @return LiquidacionContrato
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }
}