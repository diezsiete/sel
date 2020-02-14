<?php


namespace App\Exception\Novasoft\Api;


use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;


class NotFoundException extends Exception
{
    private $info;

    public function __construct(ClientException $clientException)
    {
        parent::__construct($clientException->getMessage(), $clientException->getCode());
        $this->info = $clientException->getResponse()->getInfo();
    }

    /**
     * @return array|mixed|null
     */
    public function getInfo()
    {
        return $this->info;
    }
}