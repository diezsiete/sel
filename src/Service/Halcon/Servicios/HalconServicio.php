<?php


namespace App\Service\Halcon\Servicios;


use App\Service\Configuracion\Halcon\Halcon;
use App\Service\HttpClient\HttpClient;

abstract class HalconServicio
{
    /**
     * @var Halcon
     */
    protected $configuracion;
    /**
     * @var HttpClient
     */
    protected $client;

    public function __construct(Halcon $configuracion, HttpClient $client)
    {
        $this->configuracion = $configuracion;
        $this->client = $client;
    }
}