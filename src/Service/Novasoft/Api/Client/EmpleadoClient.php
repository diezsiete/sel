<?php


namespace App\Service\Novasoft\Api\Client;


use App\Entity\Main\Empleado;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EmpleadoClient extends NovasoftApiClient
{
    /**
     * @param string $id
     * @return Empleado|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function get(string $id)
    {
        return $this->sendGet("/empleado/$id/sel");
    }
}