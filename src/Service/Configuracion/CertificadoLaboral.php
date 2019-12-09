<?php


namespace App\Service\Configuracion;


class CertificadoLaboral
{
    private $firma;

    private $firmante;

    private $cargo;

    private $contacto;


    public function __construct($firma, $firmante, $cargo, $contacto)
    {
        $this->firma =  $firma;
        $this->firmante = $firmante;
        $this->cargo = $cargo;
        $this->contacto = $contacto;
    }

    /**
     * @return string
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * @return string
     */
    public function getFirmante()
    {
        return $this->firmante;
    }

    /**
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }
}