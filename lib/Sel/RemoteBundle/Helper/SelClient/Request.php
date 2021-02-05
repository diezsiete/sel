<?php


namespace Sel\RemoteBundle\Helper\SelClient;


use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @property Body body
 */
class Request
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $env;
    /**
     * @var string|null
     */
    private $url;
    /**
     * @var Body
     */
    private $_body;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger, string $env, ?string $url = null)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->env = $env;
        $this->url = $url;
    }

    public function __get($name)
    {
        switch($name) {
            case 'body':
                return $this->getBody();
        }
        return $this;
    }

    public function __set($name, $value)
    {
        if($name === 'body') {
            $this->getBody()->setData($value);
        }
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->getBody()->setData($data);
        return $this;
    }

    public function get()
    {
        return new Response($this->httpClient, $this->request('GET', $this->url), $this->logger, $this->env);
    }

    public function post()
    {
        $body = $this->getBody()->toRequest();
        if($body instanceof FormDataPart) {
            $options = [
                'headers' => $body->getPreparedHeaders()->toArray(),
                'body' => $body->bodyToIterable(),
            ];
        } else {
            $options = ['body' => $body];
        }

        return new Response($this->httpClient, $this->request('POST', $this->url, $options), $this->logger, $this->env);
    }

    private function getBody()
    {
        if($this->_body === null) {
            $this->_body = new Body($this);
        }
        return $this->_body;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function request(string $method, string $url, array $options = [])
    {
        return $this->httpClient->request($method, $url, $options);
    }
}