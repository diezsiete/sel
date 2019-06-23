<?php


namespace App\Service\Configuracion;


class Emails
{
    /**
     * @var string|string[]
     */
    private $contacto;

    public function __construct($emails)
    {
        $this->contacto = $emails['contacto'];
    }

    /**
     * @return string|string[]
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * @return array
     */
    public function getContactoAsuntos()
    {
        return is_array($this->contacto) ? array_keys($this->contacto) : [];
    }
}