<?php


namespace App\Service\NovasoftSsrs\Entity;


class NovasoftCertificadoLaboral
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
    private $esHombre;
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

    /**
     * @param string $nombre
     * @return NovasoftCertificadoLaboral
     */
    public function setNombre(string $nombre): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setPrimerApellido(string $primerApellido): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setSegundoApellido(string $segundoApellido): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setActivo(bool $activo): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setCedula(string $cedula): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setContrato(string $contrato): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setEmpresaUsuaria(string $empresaUsuaria): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setCargo(string $cargo): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setNsalario(string $nsalario): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setSalario(string $salario): NovasoftCertificadoLaboral
    {
        $this->salario = $salario;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEsHombre(): bool
    {
        return $this->esHombre;
    }

    /**
     * @param bool $esHombre
     * @return NovasoftCertificadoLaboral
     */
    public function setEsHombre(bool $esHombre): NovasoftCertificadoLaboral
    {
        $this->esHombre = $esHombre;
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
     * @return NovasoftCertificadoLaboral
     */
    public function setFechaIngreso(\DateTime $fechaIngreso): NovasoftCertificadoLaboral
    {
        $this->fechaIngreso = $fechaIngreso;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFechaEgreso(): \DateTime
    {
        return $this->fechaEgreso;
    }

    /**
     * @param \DateTime $fechaEgreso
     * @return NovasoftCertificadoLaboral
     */
    public function setFechaEgreso(?\DateTime $fechaEgreso): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setTipoDocumento(string $tipoDocumento): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setEmail(string $email): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setFechaIngresoTextual(string $fechaIngresoTextual): NovasoftCertificadoLaboral
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
     * @return NovasoftCertificadoLaboral
     */
    public function setFechaCertificadoTextual(string $fechaCertificadoTextual): NovasoftCertificadoLaboral
    {
        $this->fechaCertificadoTextual = $fechaCertificadoTextual;
        return $this;
    }
}