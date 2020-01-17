<?php

namespace App\Entity\Halcon;

use DateTimeInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\CabezaLiquidacionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CabezaLiquidacion
{
    /**
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="App\Entity\Halcon\Vinculacion")
     * @ORM\JoinColumn(name="no_contrat", referencedColumnName="no_contrat")
     * @var Vinculacion
     */
    private $vinculacion;

    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     */
    private $liqDefini;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $nomEmplea;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $claseAuxi;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $auxiliar;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $ano;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $codCia;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nomCia;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nomCargo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nomOficin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ingreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $liquidacio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $diasEmp;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $mOperador;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $xtermina;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $bancoCta;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $ctaBanco;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $xcomisione;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $suelCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $suelPri;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $suelVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxPri;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $recCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $recPri;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $recVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $extCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $extPri;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $extVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $comCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $comPri;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $comVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $baseCes;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $basePri;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $baseVac;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totDeveng;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $totDeduci;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $neto;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $avisoNega;

    /**
     * @ORM\Column(type="string", length=78, nullable=true)
     */
    private $aviso1;

    /**
     * @ORM\Column(type="string", length=78, nullable=true)
     */
    private $aviso2;

    /**
     * @return Vinculacion
     */
    public function getVinculacion(): Vinculacion
    {
        return $this->vinculacion;
    }

    public function getLiqDefini(): ?int
    {
        return $this->liqDefini;
    }

    public function getNomEmplea(): ?string
    {
        return $this->nomEmplea;
    }

    public function getClaseAuxi(): ?string
    {
        return $this->claseAuxi;
    }

    public function getAuxiliar($format = true): ?string
    {
        return $format ? str_replace('.', '', $this->auxiliar) : $this->auxiliar;
    }

    public function getAno(): ?string
    {
        return $this->ano;
    }

    public function getCodCia(): ?string
    {
        return $this->codCia;
    }

    public function getNomCia(): ?string
    {
        return $this->nomCia;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function getNomCargo(): ?string
    {
        return $this->nomCargo;
    }

    public function getNomOficin(): ?string
    {
        return $this->nomOficin;
    }

    public function getIngreso(): ?DateTimeInterface
    {
        return $this->ingreso;
    }

    public function getLiquidacio(): ?DateTimeInterface
    {
        return $this->liquidacio;
    }

    public function getDiasEmp(): ?int
    {
        return $this->diasEmp;
    }

    public function getMOperador(): ?string
    {
        return $this->mOperador;
    }

    public function getXtermina(): ?string
    {
        return $this->xtermina;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function getBancoCta(): ?string
    {
        return $this->bancoCta;
    }

    public function getCtaBanco(): ?string
    {
        return $this->ctaBanco;
    }

    public function getXcomisione(): ?string
    {
        return $this->xcomisione;
    }

    public function getSuelCes(): ?int
    {
        return $this->suelCes;
    }

    public function getSuelPri(): ?int
    {
        return $this->suelPri;
    }

    public function getSuelVac(): ?int
    {
        return $this->suelVac;
    }

    public function getAuxCes(): ?int
    {
        return $this->auxCes;
    }

    public function getAuxPri(): ?int
    {
        return $this->auxPri;
    }

    public function getAuxVac(): ?int
    {
        return $this->auxVac;
    }

    public function getRecCes(): ?int
    {
        return $this->recCes;
    }

    public function getRecPri(): ?int
    {
        return $this->recPri;
    }

    public function getRecVac(): ?int
    {
        return $this->recVac;
    }

    public function getExtCes(): ?int
    {
        return $this->extCes;
    }

    public function getExtPri(): ?int
    {
        return $this->extPri;
    }

    public function getExtVac(): ?int
    {
        return $this->extVac;
    }

    public function getComCes(): ?int
    {
        return $this->comCes;
    }

    public function getComPri(): ?int
    {
        return $this->comPri;
    }

    public function getComVac(): ?int
    {
        return $this->comVac;
    }

    public function getBaseCes(): ?int
    {
        return $this->baseCes;
    }

    public function getBasePri(): ?int
    {
        return $this->basePri;
    }

    public function getBaseVac(): ?int
    {
        return $this->baseVac;
    }

    public function getTotDeveng(): ?int
    {
        return $this->totDeveng;
    }

    public function getTotDeduci(): ?int
    {
        return $this->totDeduci;
    }

    public function getNeto(): ?int
    {
        return $this->neto;
    }

    public function getAvisoNega(): ?string
    {
        return $this->avisoNega;
    }

    public function getAviso1(): ?string
    {
        return $this->aviso1;
    }

    public function getAviso2(): ?string
    {
        return $this->aviso2;
    }

    /**
     * @ORM\PostLoad
     */
    public function loadNullVinculacion()
    {
        try {
            $this->vinculacion->getNoContrat();
        } catch (EntityNotFoundException $e) {
            $this->vinculacion = null;
        }
    }
}
