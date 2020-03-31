<?php


namespace App\Service\Novasoft\Api\Client;


use App\Service\ExceptionHandler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
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

    public function get()
    {
        $args = func_get_args();
        $object = null;
        try {
            if ($data = $this->napiClient->get($this->classOperations['get']['path'], ['parameters' => $args])) {
                $object = $this->denormalizer->denormalize($data, $this->class);
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $object;
    }


}