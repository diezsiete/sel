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

    public function __construct($class, $classOperations, DenormalizerInterface $denormalizer, ExceptionHandler $exceptionHandler, NapiClient $napiClient)
    {
        $this->class = $class;
        $this->classOperations = $classOperations;
        $this->napiClient = $napiClient;
        $this->denormalizer = $denormalizer;
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * @return object|null
     * @throws Throwable
     */
    public function get()
    {
        $args = func_get_args();
        $object = null;
        try {
            if ($data = $this->napiClient->get($this->classOperations['get']['path'], ['parameters' => $args])) {
                $object = $this->denormalizer->denormalize($data, $this->class);
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