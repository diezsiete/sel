<?php


namespace App\Service\Napi\Client;


use App\Annotation\NapiClient\NapiResource;
use App\Annotation\NapiClient\NapiResourceId;
use App\Service\ExceptionHandler;
use App\Service\HttpClient;
use App\Service\Napi\Client\NapiClientCollectionOperation;
use App\Service\Napi\Client\NapiClientOperation;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
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
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    public function __construct(HttpClientInterface $httpClient, string $napiUrl, string $napiDb,
                                DenormalizerInterface $denormalizer, ExceptionHandler $exceptionHandler,
                                Reader $reader, PropertyAccessorInterface $propertyAccessor)
    {
        parent::__construct($httpClient);
        $this->napiUrl = $napiUrl;
        $this->napiDb = $napiDb;
        $this->denormalizer = $denormalizer;
        $this->exceptionHandler = $exceptionHandler;
        $this->reader = $reader;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function db(?string $db = null)
    {
        $this->napiDbTmp = $db;
        return $this;
    }

    public function itemOperations($class, $denormalizeAs = null)
    {
        /** @var NapiResource $annotation */
        $annotation = $this->reader->getClassAnnotation(new \ReflectionClass($class), NapiResource::class);
        return new NapiClientOperation($class, $annotation->itemOperations, $this->denormalizer, $this->exceptionHandler, $this, $denormalizeAs);
    }

    public function collectionOperations($class, $denormalizeAs = null)
    {
        /** @var NapiResource $annotation */
        $annotation = $this->reader->getClassAnnotation(new \ReflectionClass($class), NapiResource::class);
        return new NapiClientCollectionOperation($class, $annotation->collectionOperations, $this->denormalizer, $this->exceptionHandler, $this, $denormalizeAs);
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
        $parameters = $this->getParametersFromOptions($options);
        $url = $this->buildUrl($url, $parameters);
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

    /**
     * En caso de que se pase un objeto en los parametros para agregar atributos como parametros a la url
     * @param $options
     * @return array
     * @throws \ReflectionException
     */
    private function getParametersFromOptions(&$options)
    {
        $optionsParameters = $options['parameters'] ?? [];
        $parameters = [];
        if($optionsParameters) {
            foreach($optionsParameters as $key => $value) {
                if(is_object($value)) {
                    foreach((new \ReflectionClass($value))->getProperties() as $property) {
                        if ($resourceIdAnnotation = $this->reader->getPropertyAnnotation($property, NapiResourceId::class)) {
                            $parameterValue = $this->propertyAccessor->getValue($value, $property->getName());
                            $parameters[$property->getName()] = $parameterValue instanceof \DateTimeInterface
                                ? $parameterValue->format('Y-m-d') : $parameterValue;
                        }
                    }
                } else {
                    $parameters[$key] = $value;
                }
            }
        }
        unset($options['parameters']);
        return $parameters;
    }
}