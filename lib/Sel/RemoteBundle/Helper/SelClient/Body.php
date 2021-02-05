<?php

namespace Sel\RemoteBundle\Helper\SelClient;

use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @property Body file
 * @method ResponseInterface post()
 */
class Body implements \ArrayAccess
{
    /**
     * @var Request
     */
    private $request;

    private $data = [];

    private $onFile = false;

    private $hasFile = false;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __get($name)
    {
        if($name === 'file') {
            $this->onFile = true;
        }
        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this->request->$name(...$arguments);
    }

    /**
     * @param array|string $data
     * @return Body
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return FormDataPart|array|array[]
     */
    public function toRequest()
    {
        return $this->hasFile ? new FormDataPart($this->data) : $this->data;
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if($this->onFile) {
            if($value) {
                $this->data[$offset] = is_array($value) ? array_map(function ($item) {
                    return DataPart::fromPath($item);
                }, $value) : DataPart::fromPath($value);
                $this->hasFile = true;
            }
            $this->onFile = false;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}