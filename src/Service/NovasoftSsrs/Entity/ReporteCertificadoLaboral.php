<?php


namespace App\Service\NovasoftSsrs\Entity;


class ReporteCertificadoLaboral
{
    /**
     * @var string
     */
    private $nombre;
    /**
     * @var string
     */
    private $primerApellido;
    /**
     * @var string
     */
    private $segundoApellido;
    /**
     * @var boolean
     */
    private $activo;
    /**
     * @var string
     */
    private $cedula;
    /**
     * @var string
     */
    private $contrato;
    /**
     * @var string
     */
    private $empresaUsuaria;
    /**
     * @var string
     */
    private $cargo;
    /**
     * @var string
     */
    private $nsalario;
    /**
     * @var string
     */
    private $salario;
    /**
     * @var boolean
     */
    private $hombre;
    /**
     * @var \DateTime
     */
    private $fechaIngreso;
    /**
     * @var \DateTime
     */
    private $fechaEgreso;
    /**
     * @var string
     */
    private $tipoDocumento = 'Cédula de Ciudadanía';
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $fechaIngresoTextual;
    /**
     * @var string
     */
    private $fechaCertificadoTextual;

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getNombreCompleto(): string
    {
        return $this->primerApellido . " " . ($this->segundoApellido ? "$this->segundoApellido " : "") . $this->nombre;
    }
    /**
     * @param string $nombre
     * @return ReporteCertificadoLaboral
     */
    public function setNombre(string $nombre): ReporteCertificadoLaboral
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimerApellido(): string
    {
        return $this->primerApellido;
    }

    /**
     * @param string $primerApellido
     * @return ReporteCertificadoLaboral
     */
    public function setPrimerApellido(string $primerApellido): ReporteCertificadoLaboral
    {
        $this->primerApellido = $primerApellido;
        return $this;
    }

    /**
     * @return string
     */
    public function getSegundoApellido(): string
    {
        return $this->segundoApellido;
    }

    /**
     * @param string $segundoApellido
     * @return ReporteCertificadoLaboral
     */
    public function setSegundoApellido(string $segundoApellido): ReporteCertificadoLaboral
    {
        $this->segundoApellido = $segundoApellido;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActivo(): bool
    {
        return $this->activo;
    }

    /**
     * @param bool $activo
     * @return ReporteCertificadoLaboral
     */
    public function setActivo(bool $activo): ReporteCertificadoLaboral
    {
        $this->activo = $activo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCedula(): string
    {
        return $this->cedula;
    }

    /**
     * @param string $cedula
     * @return ReporteCertificadoLaboral
     */
    public function setCedula(string $cedula): ReporteCertificadoLaboral
    {
        $this->cedula = $cedula;
        return $this;
    }

    /**
     * @return string
     */
    public function getContrato(): string
    {
        return $this->contrato;
    }

    /**
     * @param string $contrato
     * @return ReporteCertificadoLaboral
     */
    public function setContrato(string $contrato): ReporteCertificadoLaboral
    {
        $this->contrato = $contrato;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmpresaUsuaria(): string
    {
        return $this->empresaUsuaria;
    }

    /**
     * @param string $empresaUsuaria
     * @return ReporteCertificadoLaboral
     */
    public function setEmpresaUsuaria(string $empresaUsuaria): ReporteCertificadoLaboral
    {
        $this->empresaUsuaria = $empresaUsuaria;
        return $this;
    }

    /**
     * @return string
     */
    public function getCargo(): string
    {
        return $this->cargo;
    }

    /**
     * @param string $cargo
     * @return ReporteCertificadoLaboral
     */
    public function setCargo(string $cargo): ReporteCertificadoLaboral
    {
        $this->cargo = $cargo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNsalario(): string
    {
        return $this->nsalario;
    }

    /**
     * @param string $nsalario
     * @return ReporteCertificadoLaboral
     */
    public function setNsalario(string $nsalario): ReporteCertificadoLaboral
    {
        $this->nsalario = $nsalario;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalario(): string
    {
        return $this->salario;
    }

    /**
     * @param string $salario
     * @return ReporteCertificadoLaboral
     */
    public function setSalario(string $salario): ReporteCertificadoLaboral
    {
        $this->salario = $salario;
        return $this;
    }

    /**
     * @return bool
     */
    public function esHombre(): bool
    {
        return $this->hombre;
    }

    /**
     * @param bool $esHombre
     * @return ReporteCertificadoLaboral
     */
    public function setHombre(bool $esHombre): ReporteCertificadoLaboral
    {
        $this->hombre = $esHombre;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaIngreso(): \DateTime
    {
        return $this->fechaIngreso;
    }

    /**
     * @param \DateTime $fechaIngreso
     * @return ReporteCertificadoLaboral
     */
    public function setFechaIngreso(\DateTime $fechaIngreso): ReporteCertificadoLaboral
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFechaEgreso(): ?\DateTime
    {
        return $this->fechaEgreso;
    }

    /**
     * @param \DateTime $fechaEgreso
     * @return ReporteCertificadoLaboral
     */
    public function setFechaEgreso(?\DateTime $fechaEgreso): ReporteCertificadoLaboral
    {
        $this->fechaEgreso = $fechaEgreso;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    /**
     * @param string $tipoDocumento
     * @return ReporteCertificadoLaboral
     */
    public function setTipoDocumento(string $tipoDocumento): ReporteCertificadoLaboral
    {
        $this->tipoDocumento = $tipoDocumento;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return ReporteCertificadoLaboral
     */
    public function setEmail(string $email): ReporteCertificadoLaboral
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFechaIngresoTextual(): string
    {
        return $this->fechaIngresoTextual;
    }

    /**
     * @param string $fechaIngresoTextual
     * @return ReporteCertificadoLaboral
     */
    public function setFechaIngresoTextual(string $fechaIngresoTextual): ReporteCertificadoLaboral
    {
        $this->fechaIngresoTextual = $fechaIngresoTextual;
        return $this;
    }

    /**
     * @return string
     */
    public function getFechaCertificadoTextual(): string
    {
        return $this->fechaCertificadoTextual;
    }

    /**
     * @param string $fechaCertificadoTextual
     * @return ReporteCertificadoLaboral
     */
    public function setFechaCertificadoTextual(string $fechaCertificadoTextual): ReporteCertificadoLaboral
    {
        $this->fechaCertificadoTextual = $fechaCertificadoTextual;
        return $this;
    }
}