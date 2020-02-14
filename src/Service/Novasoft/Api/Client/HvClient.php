<?php


namespace App\Service\Novasoft\Api\Client;


use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Entity\Hv\RedSocial;
use App\Exception\Novasoft\Api\NotFoundException;
use App\Service\Utils\Symbol;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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

    public function __construct(HttpClientInterface $httpClient, string $napiUrl, string $napiDb,
                                NormalizerInterface $normalizer, Symbol $symbol)
    {
        parent::__construct($httpClient, $napiUrl, $napiDb, $normalizer);
        $this->symbol = $symbol;
    }

    public function post(Hv $hv)
    {
        $hvNormalized = $this->normalizer->normalize($hv, null, ['groups' => ['napi:hv:post']]);
        $this->sendPost('/hv', $hvNormalized);
    }

    public function put(Hv $hv)
    {
        if($hv->getUsuario()) {
            $hvNormalized = $this->normalizer->normalize($hv, null, ['groups' => ['napi:hv:put']]);
            return $this->sendPut("/hv/{$hv->getUsuario()->getIdentificacion()}", $hvNormalized);
        }
        return null;
    }

    public function postChild(HvEntity $entity)
    {
        $childNormalized = $this->normalizer->normalize($entity, null, ['groups' => ['napi:hv-child:post']]);
        $childNormalized['hv'] = "/api/hv/{$childNormalized['hv']}";
        return $this->sendPost('/'.$this->symbol->toSnakeCase($entity), $childNormalized);
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
        return $this->sendPut("/{$this->symbol->toSnakeCase($entity)}/{$entity->getNapiId()}", $childNormalized);
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
        return $this->sendDelete("/{$this->symbol->toSnakeCase($childClass)}/{$napiId}");
    }


}