<?php

namespace App\Entity\Halcon;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Halcon\TerceroRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Tercero
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     */
    private $nitTercer;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $digitoVe;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $auxiliar;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $tipoNit;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $lugarExpe;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nombre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sexo;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $estadoCiv;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $ciudad;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $estado;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $eps;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $afp;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $afc;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $ccf;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $ciudadCcf;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $banco;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $tipoCuent;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $cuenta;

    private $nameParsed = null;

    private $primerNombre = "";

    private $segundoNombre = "";

    private $primerApellido = "";

    private $segundoApellido = "";

    public function getNitTercer(): ?int
    {
        return $this->nitTercer;
    }

    public function setNitTercer(int $nitTercer): self
    {
        $this->nitTercer = $nitTercer;

        return $this;
    }

    public function getDigitoVe(): ?bool
    {
        return $this->digitoVe;
    }

    public function setDigitoVe(?bool $digitoVe): self
    {
        $this->digitoVe = $digitoVe;

        return $this;
    }

    public function getAuxiliar(): ?int
    {
        return $this->auxiliar;
    }

    public function setAuxiliar(?int $auxiliar): self
    {
        $this->auxiliar = $auxiliar;

        return $this;
    }

    public function getTipoNit(): ?string
    {
        return $this->tipoNit;
    }

    public function setTipoNit(?string $tipoNit): self
    {
        $this->tipoNit = $tipoNit;

        return $this;
    }

    public function getLugarExpe(): ?string
    {
        return $this->lugarExpe;
    }

    public function setLugarExpe(?string $lugarExpe): self
    {
        $this->lugarExpe = $lugarExpe;

        return $this;
    }

    public function getNombre($format = true): ?string
    {
        return $format ? $this->formatName($this->nombre) : $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(?\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getSexo(): ?bool
    {
        return $this->sexo;
    }

    public function setSexo(?bool $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getEstadoCiv(): ?string
    {
        return $this->estadoCiv;
    }

    public function setEstadoCiv(?string $estadoCiv): self
    {
        $this->estadoCiv = $estadoCiv;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getEps(): ?string
    {
        return $this->eps;
    }

    public function setEps(?string $eps): self
    {
        $this->eps = $eps;

        return $this;
    }

    public function getAfp(): ?string
    {
        return $this->afp;
    }

    public function setAfp(?string $afp): self
    {
        $this->afp = $afp;

        return $this;
    }

    public function getAfc(): ?string
    {
        return $this->afc;
    }

    public function setAfc(?string $afc): self
    {
        $this->afc = $afc;

        return $this;
    }

    public function getCcf(): ?string
    {
        return $this->ccf;
    }

    public function setCcf(?string $ccf): self
    {
        $this->ccf = $ccf;

        return $this;
    }

    public function getCiudadCcf(): ?string
    {
        return $this->ciudadCcf;
    }

    public function setCiudadCcf(?string $ciudadCcf): self
    {
        $this->ciudadCcf = $ciudadCcf;

        return $this;
    }

    public function getBanco(): ?string
    {
        return $this->banco;
    }

    public function setBanco(?string $banco): self
    {
        $this->banco = $banco;

        return $this;
    }

    public function getTipoCuent(): ?string
    {
        return $this->tipoCuent;
    }

    public function setTipoCuent(?string $tipoCuent): self
    {
        $this->tipoCuent = $tipoCuent;

        return $this;
    }

    public function getCuenta(): ?string
    {
        return $this->cuenta;
    }

    public function setCuenta(?string $cuenta): self
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    private  function formatName($name){
        if(strstr($name, '//'))
            $name = str_replace('//', ' ', $name);
        $name = str_replace('/', ' ', $name);
        return $name;
    }

    /**
     * @return string
     */
    public function getPrimerNombre(): string
    {
        return $this->primerNombre;
    }

    /**
     * @return string
     */
    public function getSegundoNombre(): string
    {
        return $this->segundoNombre;
    }

    /**
     * @return string
     */
    public function getPrimerApellido(): string
    {
        return $this->primerApellido;
    }

    /**
     * @return string
     */
    public function getSegundoApellido(): string
    {
        return $this->segundoApellido;
    }

    public function getNombreCompleto()
    {
        return $this->primerNombre . ($this->segundoNombre ? " " . $this->segundoNombre : "") . " " . $this->primerApellido
            . ($this->segundoApellido ? " " . $this->segundoApellido : "");
    }

    /**
     * @ORM\PostLoad
     */
    public function parseName()
    {
        if($this->nameParsed === null && substr($this->nombre, -1) === "/") {
            $this->nameParsed = [];
            // removemos el ultimo slash del nombre, para que explode no retorne un valor adicional
            $name = substr($this->nombre, 0, strlen($this->nombre) - 1);

            $nameExplode = explode('/', $name);
            $this->primerApellido = $nameExplode[0];
            if(isset($nameExplode[1])) {
                $this->segundoApellido = $nameExplode[1];
            }

            $nombresPila = explode(" ", $nameExplode[2]);
            $this->primerNombre = $nombresPila[0];
            unset($nombresPila[0]);
            if($nombresPila) {
                $this->segundoNombre = array_reduce($nombresPila, function ($carry, $item) {
                    return $carry ? $carry . " " . $item : $carry . $item;
                }, "");
            }
        }
    }
}
