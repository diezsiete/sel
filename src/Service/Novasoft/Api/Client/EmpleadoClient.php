<?php


namespace App\Service\Novasoft\Api\Client;


use Symfony\Component\HttpClient\Exception\ClientException;

class EmpleadoClient extends NovasoftApiClient
{
    public function get(string $id)
    {
        return $this->sendGet("/empleado/$id/sel");
    }
}