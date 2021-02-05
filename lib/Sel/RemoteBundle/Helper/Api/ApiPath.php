<?php


namespace Sel\RemoteBundle\Helper\Api;


use Psr\Log\LoggerInterface;
use Sel\RemoteBundle\Helper\SelClient\Response;
use Sel\RemoteBundle\Service\SelClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Contracts\Cache\CacheInterface;

abstract class ApiPath
{
    /**
     * @var SelClient
     */
    protected $client;
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var string
     */
    protected $basePath;
    /**
     * @var string
     */
    protected $nameGlue = '-';
    /**
     * @var string[]
     */
    protected $subPath;
    /**
     * @var ApiPath[]
     */
    protected $subChilds = [];
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        SelClient $selClient,
        SerializerInterface $serializer,
        LoggerInterface $logger,
        CacheInterface $cache,
        string $basePath,
        ...$subPath
    ) {
        $this->client = $selClient;
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->basePath = $basePath;
        $this->subPath = $subPath;
        $this->cache = $cache;
    }

    public function __get($name)
    {
        if (!isset($this->subChilds[$name])) {
            $class = $this->buildChildClassPath($name);
            $subPath = $this->subPath;
            $subPath[] = $name;
            $this->subChilds[$name] = new $class(
                $this->client,
                $this->serializer,
                $this->logger,
                $this->cache,
                $this->basePath,
                ...$subPath
            );
        }
        return $this->subChilds[$name];
    }

    public function __call($name, $arguments)
    {
        if ($name === 'napi') {
            return $this->$name->empresa(...$arguments);
        }
        throw new \Exception("metodo $name no definido");
    }

    /**
     * @param string|EndPointParams|array $append
     * @param EndPointParams|array|null $params
     * @return string
     */
    public function buildPath($append = '', $params = null): string
    {
        $path = $this->basePath;
        foreach ($this->subPath as $item) {
            $path .= '/' . $this->toSnakeCase($item, $this->nameGlue);
        }
        if (is_string($append)) {
            $path .= $append;
        } elseif (!$params) {
            $params = $append;
        }

        return $path . ($params ? EndPointParams::build($params) : '');
    }

    protected function denormalizeRequestResponse(Response $response, $type, $form = null)
    {
        $responseArray = $response->toArray();
        if ($response->getStatusCode() === 400) {
            return $this->serializer->denormalize(
                $responseArray, ConstraintViolationList::class, null, $form ? ['form' => $form] : []
            );
        }
        return $this->serializer->denormalize($responseArray, $type);
    }

    /**
     * Convierte string camelCase a camel_case
     * @param $input
     * @param string $glue
     * @return string
     */
    protected function toSnakeCase($input, $glue = '_'): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode($glue, $ret);
    }

    protected function getBasePath(): string
    {
        return $this->basePath;
    }

    protected function getSubPath(): array
    {
        return $this->subPath;
    }

    protected function getClient(): SelClient
    {
        return $this->client;
    }

    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function getCache(): CacheInterface
    {
        return $this->cache;
    }

    /**
     * @return ApiPath[]
     */
    protected function &getSubChilds(): array
    {
        return $this->subChilds;
    }

    protected function buildChildClassPath($name): string
    {
        $class = substr(str_replace(['ApiPath', 'Helper\\'], '', __CLASS__), 0, -1);
        foreach ($this->subPath as $el) {
            $class .= "\\" . ucfirst($el);
        }
        $class .= "\\" . ucfirst($name);
        return $class;
    }

}