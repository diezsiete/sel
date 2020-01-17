<?php
//
//namespace App\Entity\Halcon;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\Entity(repositoryClass="App\Repository\Halcon\RenglonLiquidacionRepository")
// */
//class RenglonLiquidacion
//{
//
//    /**
//     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\Vinculacion")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $vinculacion;
//
//    /**
//     * @ORM\Column(type="string", length=6)
//     */
//    private $concepto;
//
//    /**
//     * @ORM\Column(type="string", length=40, nullable=true)
//     */
//    private $nomConcep;
//
//    /**
//     * @ORM\Column(type="string", length=9, nullable=true)
//     */
//    private $cuenta;
//
//    /**
//     * @ORM\Column(type="bigint", nullable=true)
//     */
//    private $novedad;
//
//    /**
//     * @ORM\Column(type="bigint", nullable=true)
//     */
//    private $devengado;
//
//    /**
//     * @ORM\Column(type="bigint", nullable=true)
//     */
//    private $deducido;
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getVinculacion(): ?Vinculacion
//    {
//        return $this->vinculacion;
//    }
//
//    public function setVinculacion(?Vinculacion $vinculacion): self
//    {
//        $this->vinculacion = $vinculacion;
//
//        return $this;
//    }
//
//    public function getConcepto(): ?string
//    {
//        return $this->concepto;
//    }
//
//    public function setConcepto(string $concepto): self
//    {
//        $this->concepto = $concepto;
//
//        return $this;
//    }
//
//    public function getNomConcep(): ?string
//    {
//        return $this->nomConcep;
//    }
//
//    public function setNomConcep(?string $nomConcep): self
//    {
//        $this->nomConcep = $nomConcep;
//
//        return $this;
//    }
//
//    public function getCuenta(): ?string
//    {
//        return $this->cuenta;
//    }
//
//    public function setCuenta(?string $cuenta): self
//    {
//        $this->cuenta = $cuenta;
//
//        return $this;
//    }
//
//    public function getNovedad(): ?int
//    {
//        return $this->novedad;
//    }
//
//    public function setNovedad(?int $novedad): self
//    {
//        $this->novedad = $novedad;
//
//        return $this;
//    }
//
//    public function getDevengado(): ?int
//    {
//        return $this->devengado;
//    }
//
//    public function setDevengado(?int $devengado): self
//    {
//        $this->devengado = $devengado;
//
//        return $this;
//    }
//
//    public function getDeducido(): ?int
//    {
//        return $this->deducido;
//    }
//
//    public function setDeducido(?int $deducido): self
//    {
//        $this->deducido = $deducido;
//
//        return $this;
//    }
//}
