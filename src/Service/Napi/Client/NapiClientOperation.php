<?php


namespace App\Service\Napi\Client;


use App\Service\ExceptionHandler;
use App\Service\Napi\Client\NapiClient;
use ReflectionException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

/**
 * Class NapiClientOperation
 * @package App\Service\Novasoft\Api\Client
 * @method object|null get(...$args)
 */
class NapiClientOperation
{
    protected $class;
    protected $classOperations;
    protected $buildFromObject;
    /**
     * @var NapiClient
     */
    protected $napiClient;
    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;
    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandler;
    /**
     * @var null|string|false
     */
    protected $denormalizeAs;

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

    public function __call($name, $args)
    {
        $methodName = "_$name";
        if(!method_exists($this, $methodName)) {
            $methodName = '_get';
        }

        if (count($args) === 1 && is_array($args[0])) {
            $args = $args[0];
        }
        if ($this->buildFromObject) {
            $args[] = $this->buildFromObject;
        }
        $object = null;
        try {
            $path = $this->classOperations[$name]['path'];
            $queryParameters = $this->classOperations[$name]['queryParameters'] ?? [];
            if ($queryParameters) {
                $path .= array_reduce($queryParameters, static function ($carry, $item) use ($args) {
                    if (isset($args[$item])) {
                        return $carry . ($carry ? '&' : '?') . $item . '={' . $item . '}';
                    }
                    return '';
                }, '');
            }
            $object = $this->$methodName($path, $args);

        } catch (ClientExceptionInterface $e) {
            if ($e->getCode() !== 404) {
                $this->exceptionHandler->handle($e);
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $object;
    }

    /**
     * @param $path
     * @param $args
     * @return object|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ReflectionException
     */
    protected function _get($path, $args)
    {
        $object = null;
        if ($data = $this->napiClient->get($path, ['parameters' => $args])) {
            $object = $this->denormalizeAs
                ? $this->denormalizer->denormalize($data, $this->denormalizeAs)
                : $data;
        }
        return $object;
    }


}