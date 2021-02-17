<?php

namespace Sel\RemoteBundle\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @property string url
 * @property string empresa
 */
class Configuracion
{
    /**
     * @var array
     */
    private $config;

    public function __construct(ParameterBagInterface $param)
    {
        $this->config = $param->get('selr');
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        throw new \Exception("$name no existe");
    }

    public function getBasePath(): string
    {
        return $this->empresa;
    }
}