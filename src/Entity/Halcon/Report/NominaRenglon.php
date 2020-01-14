<?php
namespace App\Entity\Halcon\Report;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class NominaRenglon
{
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $contrato;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $consecutivo;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $concepto;
    /**
     * @ORM\Column()
     */
    public $novedad;
    /**
     * @ORM\Column()
     */
    public $novedadEn;
    /**
     * @ORM\Id()
     * @ORM\Column()
     */
    public $total;
}