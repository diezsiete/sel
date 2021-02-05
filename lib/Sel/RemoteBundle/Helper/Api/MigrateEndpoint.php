<?php


namespace Sel\RemoteBundle\Helper\Api;


use Sel\RemoteBundle\Service\SelClient;
use Symfony\Component\Serializer\SerializerInterface;

trait MigrateEndpoint
{
    public function migrate($object)
    {
        $request = $this->getClient()->request($this->buildPath());
        $request->body = $this->getSerializer()->serialize($object, 'json');
        return $request->post()->toArray();
    }

    abstract protected function getClient(): SelClient;

    abstract public function buildPath($append = '', $params = null): string;

    abstract protected function getSerializer(): SerializerInterface;
}