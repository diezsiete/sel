<?php

namespace App\Service\Novasoft\Api\Client;

use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Exception\Novasoft\Api\NotFoundException;
use App\Service\ExceptionHandler;
use App\Service\Utils\Symbol;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HvClient extends NovasoftApiClient
{
    /**
     * @var Symbol
     */
    private $symbol;

    public function __construct(HttpClientInterface $httpClient, string $napiUrl, string $napiDb, NormalizerInterface $normalizer,
                                DenormalizerInterface $denormalizer, ExceptionHandler $exceptionHandler, Symbol $symbol)
    {
        parent::__construct($httpClient, $napiUrl, $napiDb, $normalizer, $denormalizer, $exceptionHandler);
        $this->symbol = $symbol;
    }

    /**
     * @param Hv|string $hv objeto o identificacion
     * @return mixed|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function get($hv)
    {
        $identificacion = is_object($hv) ? $hv->getUsuario()->getIdentificacion() : $hv;
        return $this->sendGet('/hv/' . $identificacion);
    }

    /**
     * @param Hv $hv
     * @return array
     * @throws ExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function post(Hv $hv)
    {
        $hvNormalized = $this->normalizer->normalize($hv, null, ['groups' => ['napi:hv:post']]);
        return $this->sendPost('/hv', $hvNormalized);
    }

    public function put(Hv $hv)
    {
        if($hv->getUsuario()) {
            $hvNormalized = $this->normalizer->normalize($hv, null, ['groups' => ['napi:hv:put']]);
            return $this->sendPut("/hv/{$hv->getUsuario()->getIdentificacion()}", $hvNormalized);
        }
        return null;
    }

    /**
     * @param Hv|string $hv objeto o identificacion
     * @return int
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws NotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function delete($hv)
    {
        $identificacion = is_object($hv) ? $hv->getUsuario()->getIdentificacion() : $hv;
        return $this->sendDelete('/hv/' . $identificacion);
    }


    /**
     * @param Hv|string $hv
     * @param $childClassName
     */
    public function getChild($hv, $childClassName)
    {
        $identificacion = is_object($hv) ? $hv->getUsuario()->getIdentificacion() : $hv;
        return $this->sendGet('/' . $this->symbol->toSnakeCase($childClassName, '-'));
    }

    public function postChild(HvEntity $entity)
    {
        $childNormalized = $this->normalizer->normalize($entity, null, ['groups' => ['napi:hv-child:post']]);
        $childNormalized['hv'] = $this->buildUrl("/hv/{$childNormalized['hv']}");
        return $this->sendPost('/'.$this->symbol->toSnakeCase($entity, '-'), $childNormalized);
    }

    /**
     * @param HvEntity $entity
     * @return array
     * @throws NotFoundException
     * @throws ExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function putChild(HvEntity $entity)
    {
        $childNormalized = $this->normalizer->normalize($entity, null, ['groups' => ['napi:hv-child:put']]);
        return $this->sendPut("/{$this->symbol->toSnakeCase($entity, '-')}/{$entity->getNapiId()}", $childNormalized);
    }

    /**
     * @param string $napiId
     * @param string $childClass
     * @return int
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws NotFoundException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function deleteChild(string $napiId, string $childClass)
    {
        return $this->sendDelete("/{$this->symbol->toSnakeCase($childClass, '-')}/{$napiId}");
    }

    public function saveReferencias(HvEntity $hvEntity)
    {
        $hv = $hvEntity->getHv();
        if($hv && $hv->getUsuario()) {
            $hvNormalized = $this->normalizer->normalize($hvEntity->getHv(), null, ['groups' => ['napi:referencia:post']]);
            return $this->sendPut("/hv/{$hv->getUsuario()->getIdentificacion()}", $hvNormalized);
        }
        return null;
    }

    public function deleteReferencias(HvEntity $hvEntity)
    {
        $hv = $hvEntity->getHv();
        if($hv && $hv->getUsuario()) {
            return $this->sendPut("/hv/{$hv->getUsuario()->getIdentificacion()}", ['referencias' => []]);
        }
        return null;
    }
}