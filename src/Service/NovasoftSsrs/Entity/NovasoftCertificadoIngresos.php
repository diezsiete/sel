<?php


namespace App\Service\NovasoftSsrs\Entity;


class NovasoftCertificadoIngresos
{
    private $DV;
    private $primerApellido;
    private $segundoApellido;
    private $nombres;
    private $periodoCertificacionDe = [];
    private $periodoCertificacionA = [];
    private $fechaExpedicion = [];
    private $lugarRetencion;
    private $codigoDepartamento = [];
    private $codigoCiudad = [];
    private $pagosSalarios;
    private $pagosHonorarios;
    private $pagosServicios;
    private $pagosComisiones;
    private $pagosPrestaciones;
    private $pagosViaticos;
    private $pagosRepresentacion;
    private $pagosCompensaciones;
    private $pagosOtros;
    private $pagosCesantias;
    private $pagosJubilacion;
    private $totalIngresosBrutos;
    private $aportesSalud;
    private $aportesPensionObligatorio;
    private $aportesPensionVoluntario;
    private $aportesAFC;
    private $valorRetencionFuente;
    private $pagadorNombre;
    private $pagadorNit;
    private $totalTexto;
    private $total;
    private $certificoTexto = [];

    /**
     * @return mixed
     */
    public function getDV()
    {
        return $this->DV;
    }

    /**
     * @param mixed $DV
     * @return NovasoftCertificadoIngresos
     */
    public function setDV($DV)
    {
        $this->DV = $DV;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * @param mixed $primerApellido
     * @return NovasoftCertificadoIngresos
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * @param mixed $segundoApellido
     * @return NovasoftCertificadoIngresos
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * @param mixed $nombres
     * @return NovasoftCertificadoIngresos
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
        return $this;
    }

    /**
     * @return array
     */
    public function getPeriodoCertificacionDe(): array
    {
        return $this->periodoCertificacionDe;
    }

    /**
     * @param string $periodoCertificacionDe
     * @return NovasoftCertificadoIngresos
     */
    public function setPeriodoCertificacionDe(string $periodoCertificacionDe): NovasoftCertificadoIngresos
    {
        $this->periodoCertificacionDe[] = $periodoCertificacionDe;
        return $this;
    }

    /**
     * @return array
     */
    public function getPeriodoCertificacionA(): array
    {
        return $this->periodoCertificacionA;
    }

    /**
     * @param string $periodoCertificacionA
     * @return NovasoftCertificadoIngresos
     */
    public function setPeriodoCertificacionA(string $periodoCertificacionA): NovasoftCertificadoIngresos
    {
        $this->periodoCertificacionA[] = $periodoCertificacionA;
        return $this;
    }

    /**
     * @return array
     */
    public function getFechaExpedicion(): array
    {
        return $this->fechaExpedicion;
    }

    /**
     * @param string $fechaExpedicion
     * @return NovasoftCertificadoIngresos
     */
    public function setFechaExpedicion(string $fechaExpedicion): NovasoftCertificadoIngresos
    {
        $this->fechaExpedicion[] = $fechaExpedicion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLugarRetencion()
    {
        return $this->lugarRetencion;
    }

    /**
     * @param mixed $lugarRetencion
     * @return NovasoftCertificadoIngresos
     */
    public function setLugarRetencion($lugarRetencion)
    {
        $this->lugarRetencion = $lugarRetencion;
        return $this;
    }

    /**
     * @return array
     */
    public function getCodigoDepartamento(): array
    {
        return $this->codigoDepartamento;
    }

    /**
     * @param string $codigoDepartamento
     * @return NovasoftCertificadoIngresos
     */
    public function setCodigoDepartamento(string $codigoDepartamento): NovasoftCertificadoIngresos
    {
        $this->codigoDepartamento[] = $codigoDepartamento;
        return $this;
    }

    /**
     * @return array
     */
    public function getCodigoCiudad(): array
    {
        return $this->codigoCiudad;
    }

    /**
     * @param string $codigoCiudad
     * @return NovasoftCertificadoIngresos
     */
    public function setCodigoCiudad(string $codigoCiudad): NovasoftCertificadoIngresos
    {
        $this->codigoCiudad[] = $codigoCiudad;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosSalarios()
    {
        return $this->pagosSalarios;
    }

    /**
     * @param mixed $pagosSalarios
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosSalarios($pagosSalarios)
    {
        $this->pagosSalarios = $pagosSalarios;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosHonorarios()
    {
        return $this->pagosHonorarios;
    }

    /**
     * @param mixed $pagosHonorarios
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosHonorarios($pagosHonorarios)
    {
        $this->pagosHonorarios = $pagosHonorarios;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosServicios()
    {
        return $this->pagosServicios;
    }

    /**
     * @param mixed $pagosServicios
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosServicios($pagosServicios)
    {
        $this->pagosServicios = $pagosServicios;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosComisiones()
    {
        return $this->pagosComisiones;
    }

    /**
     * @param mixed $pagosComisiones
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosComisiones($pagosComisiones)
    {
        $this->pagosComisiones = $pagosComisiones;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosPrestaciones()
    {
        return $this->pagosPrestaciones;
    }

    /**
     * @param mixed $pagosPrestaciones
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosPrestaciones($pagosPrestaciones)
    {
        $this->pagosPrestaciones = $pagosPrestaciones;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosViaticos()
    {
        return $this->pagosViaticos;
    }

    /**
     * @param mixed $pagosViaticos
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosViaticos($pagosViaticos)
    {
        $this->pagosViaticos = $pagosViaticos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosRepresentacion()
    {
        return $this->pagosRepresentacion;
    }

    /**
     * @param mixed $pagosRepresentacion
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosRepresentacion($pagosRepresentacion)
    {
        $this->pagosRepresentacion = $pagosRepresentacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosCompensaciones()
    {
        return $this->pagosCompensaciones;
    }

    /**
     * @param mixed $pagosCompensaciones
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosCompensaciones($pagosCompensaciones)
    {
        $this->pagosCompensaciones = $pagosCompensaciones;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosOtros()
    {
        return $this->pagosOtros;
    }

    /**
     * @param mixed $pagosOtros
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosOtros($pagosOtros)
    {
        $this->pagosOtros = $pagosOtros;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosCesantias()
    {
        return $this->pagosCesantias;
    }

    /**
     * @param mixed $pagosCesantias
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosCesantias($pagosCesantias)
    {
        $this->pagosCesantias = $pagosCesantias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagosJubilacion()
    {
        return $this->pagosJubilacion;
    }

    /**
     * @param mixed $pagosJubilacion
     * @return NovasoftCertificadoIngresos
     */
    public function setPagosJubilacion($pagosJubilacion)
    {
        $this->pagosJubilacion = $pagosJubilacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalIngresosBrutos()
    {
        return $this->totalIngresosBrutos;
    }

    /**
     * @param mixed $totalIngresosBrutos
     * @return NovasoftCertificadoIngresos
     */
    public function setTotalIngresosBrutos($totalIngresosBrutos)
    {
        $this->totalIngresosBrutos = $totalIngresosBrutos;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAportesSalud()
    {
        return $this->aportesSalud;
    }

    /**
     * @param mixed $aportesSalud
     * @return NovasoftCertificadoIngresos
     */
    public function setAportesSalud($aportesSalud)
    {
        $this->aportesSalud = $aportesSalud;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAportesPensionObligatorio()
    {
        return $this->aportesPensionObligatorio;
    }

    /**
     * @param mixed $aportesPensionObligatorio
     * @return NovasoftCertificadoIngresos
     */
    public function setAportesPensionObligatorio($aportesPensionObligatorio)
    {
        $this->aportesPensionObligatorio = $aportesPensionObligatorio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAportesPensionVoluntario()
    {
        return $this->aportesPensionVoluntario;
    }

    /**
     * @param mixed $aportesPensionVoluntario
     * @return NovasoftCertificadoIngresos
     */
    public function setAportesPensionVoluntario($aportesPensionVoluntario)
    {
        $this->aportesPensionVoluntario = $aportesPensionVoluntario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAportesAFC()
    {
        return $this->aportesAFC;
    }

    /**
     * @param mixed $aportesAFC
     * @return NovasoftCertificadoIngresos
     */
    public function setAportesAFC($aportesAFC)
    {
        $this->aportesAFC = $aportesAFC;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorRetencionFuente()
    {
        return $this->valorRetencionFuente;
    }

    /**
     * @param mixed $valorRetencionFuente
     * @return NovasoftCertificadoIngresos
     */
    public function setValorRetencionFuente($valorRetencionFuente)
    {
        $this->valorRetencionFuente = $valorRetencionFuente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagadorNombre()
    {
        return $this->pagadorNombre;
    }

    /**
     * @param mixed $pagadorNombre
     * @return NovasoftCertificadoIngresos
     */
    public function setPagadorNombre($pagadorNombre)
    {
        $this->pagadorNombre = $pagadorNombre;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagadorNit()
    {
        return $this->pagadorNit;
    }

    /**
     * @param mixed $pagadorNit
     * @return NovasoftCertificadoIngresos
     */
    public function setPagadorNit($pagadorNit)
    {
        $this->pagadorNit = $pagadorNit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalTexto()
    {
        return $this->totalTexto;
    }

    /**
     * @param mixed $totalTexto
     * @return NovasoftCertificadoIngresos
     */
    public function setTotalTexto($totalTexto)
    {
        $this->totalTexto = $totalTexto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     * @return NovasoftCertificadoIngresos
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return array
     */
    public function getCertificoTexto(): array
    {
        return $this->certificoTexto;
    }

    /**
     * @param string $certificoTexto
     * @return NovasoftCertificadoIngresos
     */
    public function setCertificoTexto(string $certificoTexto): NovasoftCertificadoIngresos
    {
        $this->certificoTexto[] = $certificoTexto;
        return $this;
    }


}