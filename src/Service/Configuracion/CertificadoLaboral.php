<?php


namespace App\Service\Configuracion;


class CertificadoLaboral
{
    private $firma;

    private $firmante;

    private $cargo;

    private $contacto;

    private $webDir;

    public function __construct($data, $webDir)
    {
        $this->firma =  $data['firma'];
        $this->firmante = $data['firmante'];
        $this->cargo = $data['firmante_cargo'];
        $this->contacto = $data['firmante_contacto'];
        $this->webDir = $webDir;
    }

    /**
     * @return string
     */
    public function getFirma($fullPath = false)
    {
        return $fullPath ? $this->webDir . $this->firma : $this->firma;
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