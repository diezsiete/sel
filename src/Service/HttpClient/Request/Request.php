<?php


namespace App\Service\HttpClient\Request;


use App\Helper\HttpClient\Body;
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
     * @var string|null
     */
    private $url;
    /**
     * @var Body
     */
    private $_body;

    public function __construct(HttpClientInterface $httpClient, ?string $url = null)
    {
        $this->httpClient = $httpClient;
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
        return $this->request('GET', $this->url);
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

        return $this->request('POST', $this->url, $options);
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