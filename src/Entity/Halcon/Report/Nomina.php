<?php
namespace App\Entity\Halcon\Report;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class Nomina
{
    /**
     * @ORM\Column()
     */
    public $usuario;
    /**
     * @ORM\Column()
     */
    public $companiaEmpresa;
    /**
     * @ORM\Column()
     */
    public $nit;
    /**
     * @ORM\Column()
     */
    public $centroCostos;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $consecutivoLiquidacion;
    /**
     * @ORM\Column()
     */
    public $nombre;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $documento;
    /**
     * @ORM\Column()
     */
    public $cargo;
    /**
     * @ORM\Column()
     */
    public $periodo;
    /**
     * @ORM\Column()
     */
    public $basico;
    /**
     * @ORM\Column()
     */
    public $banco;
    /**
     * @ORM\Column()
     */
    public $cuenta;
    /**
     * @ORM\Column()
     */
    public $mensaje;

    public $devengados = [];

    public $deducciones = [];

}