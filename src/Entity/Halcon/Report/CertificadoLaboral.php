<?php
namespace App\Entity\Halcon\Report;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class CertificadoLaboral
{
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $identificacion;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $contrato;
    /**
     * @ORM\Column()
     */
    public $usuario;
    /**
     * @ORM\Column()
     */
    public $convenio;
    /**
     * @ORM\Column()
     */
    public $empresaTelefono;
    /**
     * @ORM\Column()
     */
    public $empresaDireccion;
    /**
     * @ORM\Column()
     */
    public $empresaNit;
    /**
     * @ORM\Column()
     */
    public $cargo;
    /**
     * @var DateTimeInterface
     * @ORM\Column(type="date")
     */
    public $fechaIngreso;
    /**
     * @var DateTimeInterface|null
     * @ORM\Column(type="date", nullable=true)
     */
    public $fechaRetiro;
    /**
     * @ORM\Column()
     */
    public $salario;
}