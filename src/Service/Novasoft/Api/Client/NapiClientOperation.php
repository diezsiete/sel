<?php


namespace App\Service\Novasoft\Api\Client;


use App\Service\ExceptionHandler;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class NapiClientOperation
{
    private $class;
    private $classOperations;
    private $buildFromObject;
    /**
     * @var NapiClient
     */
    private $napiClient;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    /**
     * @var ExceptionHandler
     */
    private $exceptionHandler;
    /**
     * @var null|string|false
     */
    private $denormalizeAs;

    public function __construct($class, $classOperations, DenormalizerInterface $denormalizer, ExceptionHandler $exceptionHandler,
                                NapiClient $napiClient, $denormalizeAs = null)
    {
        if(is_object($class)) {
            $this->class = get_class($class);
            $this->buildFromObject = $class;
        } else {
            $this->class = $class;
        }
        $this->classOperations = $classOperations;
        $this->napiClient = $napiClient;
        $this->denormalizer = $denormalizer;
        $this->exceptionHandler = $exceptionHandler;
        $this->denormalizeAs = $denormalizeAs;
        if($this->denormalizeAs === null) {
            $this->denormalizeAs = $this->class;
        }
    }

    /**
     * @return object|null
     * @throws Throwable
     */
    public function get()
    {
        $args = func_get_args();
        if($this->buildFromObject) {
            $args[] = $this->buildFromObject;
        }
        $object = null;
        try {
            if ($data = $this->napiClient->get($this->classOperations['get']['path'], ['parameters' => $args])) {
                $object = $this->denormalizeAs
                    ? $this->denormalizer->denormalize($data, $this->denormalizeAs)
                    : $data;
            }
        } catch (ClientExceptionInterface $e) {
            if($e->getCode() !== 404) {
                $this->exceptionHandler->handle($e);
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $object;
    }


}