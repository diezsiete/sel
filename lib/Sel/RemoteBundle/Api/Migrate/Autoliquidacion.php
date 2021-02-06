<?php


namespace Sel\RemoteBundle\Api\Migrate;


use Sel\RemoteBundle\Helper\Api\ApiPath;
use Sel\RemoteBundle\Helper\Api\MigrateEndpoint;

class Autoliquidacion extends ApiPath
{
    use MigrateEndpoint;

    public function migrateUsuario($object, $params = null)
    {
        $request = $this->getClient()->request($this->buildPath('/usuario'));
        return $this->migrate($object, $params, $request);
    }
}