<?php


namespace App\Service\Configuracion;


class Oficina
{
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

    public function __construct($ciudad, $direccion, $telefono, $email, $latitude, $longitude)
    {
        $this->ciudad = $ciudad;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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
}