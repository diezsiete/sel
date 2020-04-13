<?php


namespace App\Service\Napi\Report;


use App\Service\Novasoft\Api\Client\NapiClient;

class Importer
{
    /**
     * @var NapiClient
     */
    private $napiClient;

    public function __construct(NapiClient $napiClient)
    {
        $this->napiClient = $napiClient;
    }
}