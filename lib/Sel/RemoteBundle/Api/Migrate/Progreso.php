<?php

namespace Sel\RemoteBundle\Api\Migrate;

use App\Entity\Evaluacion\Respuesta\Respuesta;
use Sel\RemoteBundle\Helper\Api\ApiPath;
use Sel\RemoteBundle\Helper\Api\EndPointParams;
use Sel\RemoteBundle\Helper\Api\MigrateEndpoint;
use Sel\RemoteBundle\Helper\SelClient\Request;

class Progreso extends ApiPath
{
    use MigrateEndpoint;

    public function migrateRespuesta(Respuesta $object, $params = null, ?Request $request = null)
    {
        $request = $request ?? $this->getClient()->request($this->buildPath('/respuesta'));
        $request->body = $this->getSerializer()->serialize($object, 'json', EndPointParams::buildContext($params));
        return $request->post()->toArray();
    }
}