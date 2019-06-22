<?php


namespace App\Service\Configuracion;


class SsrsDb
{
    /**
     * @var string
     */
    private $nombre;
    /**
     * @var bool
     */
    private $convenios;

    public function __construct($nombre, $ssrsDbConfig)
    {
        $this->nombre = $nombre;
        $this->convenios = $ssrsDbConfig['convenios'];
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @return bool
     */
    public function hasConvenios(): bool
    {
        return $this->convenios;
    }
}