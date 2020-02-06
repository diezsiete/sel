<?php


namespace App\Service\Novasoft\Api\Importer;


use App\Service\Novasoft\Api\NovasoftApiClient;

class EmpleadoImporter
{
    /**
     * @var NovasoftApiClient
     */
    private $client;

    public function __construct(NovasoftApiClient $client)
    {
        $this->client = $client;
    }

    public function import($identificacion)
    {
        $data = $this->client->empleado($identificacion);
        dump($data);
    }
}