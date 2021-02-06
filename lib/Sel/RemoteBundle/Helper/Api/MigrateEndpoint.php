<?php


namespace Sel\RemoteBundle\Helper\Api;


use Sel\RemoteBundle\Service\SelClient;
use Symfony\Component\Serializer\SerializerInterface;

trait MigrateEndpoint
{
    /**
     * @param $object
     * @param EndPointParams|array  $params
     * @return array|null
     */
    public function migrate($object, $params = null)
    {
        $request = $this->getClient()->request($this->buildPath());
        $request->body = $this->getSerializer()->serialize($object, 'json', EndPointParams::buildContext($params));
        return $request->post()->toArray();
    }

    abstract protected function getClient(): SelClient;

    abstract public function buildPath($append = '', $params = null): string;

    abstract protected function getSerializer(): SerializerInterface;
}