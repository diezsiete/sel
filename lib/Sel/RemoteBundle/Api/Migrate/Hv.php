<?php

namespace Sel\RemoteBundle\Api\Migrate;

use Sel\RemoteBundle\Helper\Api\ApiPath;
use Sel\RemoteBundle\Helper\Api\MigrateEndpoint;

class Hv extends ApiPath
{
    use MigrateEndpoint;

    public function estudios($identificacion, $object, $params = null)
    {
        $request = $this->getClient()->request($this->buildPath("/$identificacion/estudios"));
        return $this->migrate($object, $params, $request);
    }
    public function experiencias($identificacion, $object, $params = null)
    {
        $request = $this->getClient()->request($this->buildPath("/$identificacion/experiencias"));
        return $this->migrate($object, $params, $request);
    }
    public function adjunto($identificacion, $object, $params = null)
    {
        $request = $this->getClient()->request($this->buildPath("/$identificacion/adjunto"));
        return $this->migrate($object, $params, $request);
    }
}