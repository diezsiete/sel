<?php


namespace App\Service\Novasoft\Api\Client;


use App\Annotation\NapiClient\NapiResource;
use App\Service\ExceptionHandler;
use App\Service\HttpClient;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NapiClient extends HttpClient
{
    /**
     * @var string
     */
    private $napiUrl;
    /**
     * @var string
     */
    private $napiDb;
    /**
     * @var string
     */
    private $napiDbTmp;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    /**
     * @var ExceptionHandler
     */
    private $exceptionHandler;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Reader
     */
    private $reader;

    public function __construct(HttpClientInterface $httpClient, string $napiUrl, string $napiDb,
                                NormalizerInterface $normalizer, DenormalizerInterface $denormalizer,
                                ExceptionHandler $exceptionHandler, Reader $reader)
    {
        parent::__construct($httpClient);
        $this->napiUrl = $napiUrl;
        $this->napiDb = $napiDb;
        $this->denormalizer = $denormalizer;
        $this->exceptionHandler = $exceptionHandler;
        $this->reader = $reader;
    }

    public function db(?string $db = null)
    {
        $this->napiDbTmp = $db;
        return $this;
    }

    public function itemOperations($class)
    {
        /** @var NapiResource $annotation */
        $annotation = $this->reader->getClassAnnotation(new \ReflectionClass($class), NapiResource::class);
        return new NapiClientOperation($class, $annotation->itemOperations, $this->denormalizer, $this->exceptionHandler, $this);
    }

    public function collectionOperations($class)
    {
        /** @var NapiResource $annotation */
        $annotation = $this->reader->getClassAnnotation(new \ReflectionClass($class), NapiResource::class);
        return new NapiClientOperation($class, $annotation->collectionOperations, $this->denormalizer, $this->exceptionHandler, $this);
    }

    /**
     * @param string $url
     * @param array $options
     * @return array|\Symfony\Contracts\HttpClient\ResponseInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function get(string $url, array $options = [])
    {
        $url = $this->buildUrl($url, $options['parameters'] ?? []);
        unset($options['parameters']);
        $response = parent::get($url, $options);
        return $response->toArray();
    }

    protected function buildUrl($url, $parameters = []): string
    {
        if($parameters) {
            $url = $this->addParametersToUrl($url, $parameters, '{}');
        }
        $db = $this->napiDbTmp ?: $this->napiDb;
        $base = "/{$db}/api";

        //urls de hydra collection ya vienen con $base, eliminamos
        $url = str_replace($base, '', $url);

        return "{$this->napiUrl}/{$db}/api{$url}";
    }
}