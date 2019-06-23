<?php


namespace App\Service\Configuracion;


class Oficina
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    public $ciudad;
    /**
     * @var string
     */
    public $direccion;
    /**
     * @var string
     */
    public $telefono;
    /**
     * @var string
     */
    public $email;
    /**
     * @var float
     */
    public $latitude;
    /**
     * @var float
     */
    public $longitude;

    /**
     * @var boolean
     */
    public $principal;

    public function __construct($nombre, $oficinaData)
    {
        $this->nombre = $nombre;
        $this->ciudad = $oficinaData['ciudad'];
        $this->direccion = $oficinaData['direccion'];
        $this->telefono = $oficinaData['telefono'];
        $this->email = $oficinaData['email'];
        $this->latitude = $oficinaData['latitude'];
        $this->longitude = $oficinaData['longitude'];
        $this->principal = $oficinaData['principal'];
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    /**
     * @return string
     */
    public function getDireccion(): string
    {
        return $this->direccion;
    }

    /**
     * @return string
     */
    public function getTelefono(): string
    {
        return $this->telefono;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return bool
     */
    public function isPrincipal(): bool
    {
        return $this->principal;
    }
}