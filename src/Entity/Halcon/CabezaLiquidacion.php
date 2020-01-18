<?php

namespace App\Entity\Halcon;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var DateTime
     */
    private $ingreso;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Halcon\Banco")
     * @ORM\JoinColumn(name="banco", referencedColumnName="banco")
     * @var Banco
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
     *
     * @var RenglonLiquidacion[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Halcon\RenglonLiquidacion", mappedBy="cabeza")
     */
    private $renglones;

    public function __construct()
    {
        $this->renglones = new ArrayCollection();
    }

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
        return trim($format ? str_replace('.', '', $this->auxiliar) : $this->auxiliar);
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
        return trim($this->xtermina);
    }

    /**
     * @return Banco
     */
    public function getBanco()
    {
        return $this->banco;
    }

    public function getBancoCta(): ?string
    {
        return $this->bancoCta;
    }

    public function getCtaBanco(): ?string
    {
        return trim($this->ctaBanco);
    }

    public function getXcomisione(): ?string
    {
        return $this->xcomisione;
    }

    public function getSuelCes($format = true)
    {
        return $format ? number_format($this->suelCes) : $this->suelCes;
    }

    public function getSuelPri($format = true)
    {
        return $format ? number_format($this->suelPri) : $this->suelPri;
    }

    public function getSuelVac($format = true)
    {
        return $format ? number_format($this->suelVac) : $this->suelVac;
    }

    public function getAuxCes($format = true)
    {
        return $format ? number_format($this->auxCes) : $this->auxCes;
    }

    public function getAuxPri($format = true)
    {
        return $format ? number_format($this->auxPri) : $this->auxPri;
    }

    public function getAuxVac($format = true)
    {
        return $format ? number_format($this->auxVac) : $this->auxVac;
    }

    public function getRecCes($format = true)
    {
        return $format ? number_format($this->recCes) : $this->recCes ;
    }

    public function getRecPri($format = true)
    {
        return $format ? number_format($this->recPri) : $this->recPri;
    }

    public function getRecVac($format = true)
    {
        return $format ? number_format($this->recVac) : $this->recVac;
    }

    public function getExtCes($format = true)
    {
        return $format ? number_format($this->extCes) : $this->extCes;
    }

    public function getExtPri($format = true)
    {
        return $format ? number_format($this->extPri) : $this->extPri;
    }

    public function getExtVac($format = true)
    {
        return $format ? number_format($this->extVac) : $this->extVac;
    }

    public function getComCes($format = true)
    {
        return $format ? number_format($this->comCes) : $this->comCes;
    }

    public function getComPri($format = true)
    {
        return $format ? number_format($this->comPri) : $this->comPri;
    }

    public function getComVac($format = true)
    {
        return $format ? number_format($this->comVac) : $this->comVac;
    }

    public function getBaseCes($format = true)
    {
        return $format ? number_format($this->baseCes) : $this->baseCes;
    }

    public function getBasePri($format = true)
    {
        return $format ? number_format($this->basePri) : $this->basePri;
    }

    public function getBaseVac($format = true)
    {
        return $format ? number_format($this->baseVac) : $this->baseVac;
    }

    public function getTotDeveng($format = true)
    {
        return $format ? number_format($this->totDeveng) : $this->totDeveng;
    }

    public function getTotDeduci($format = true)
    {
        return $format ? number_format($this->totDeduci) : $this->totDeduci;
    }

    public function getNeto($format = true)
    {
        return $format ? number_format($this->neto) : $this->neto;
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
     * @return RenglonLiquidacion[]|ArrayCollection
     */
    public function getRenglones()
    {
        return $this->renglones;
    }

    /**
     * @ORM\PostLoad
     */
    public function loadNull()
    {
        try {
            $this->vinculacion->getNoContrat();
        } catch (EntityNotFoundException $e) {
            $this->vinculacion = null;
        }
        try {
            $this->banco->getNombre();
        } catch (EntityNotFoundException $e) {
            $this->banco = new Banco("SIN DEFINIR");
        }
    }

    public function getFecha()
    {
        return $this->ingreso->format('Y-m-d') . ' / ' . $this->liquidacio->format('Y-m-d');
    }
}
