<?php

namespace Sel\RemoteBundle\Api\Migrate;

use Sel\RemoteBundle\Helper\Api\ApiPath;
use Sel\RemoteBundle\Helper\Api\EndPointParams;
use Sel\RemoteBundle\Helper\Api\MigrateEndpoint;

class Convenio extends ApiPath
{
    use MigrateEndpoint {
        migrate as traitMigrate;
    }

    public function migrate($object, $params = null)
    {
        $normalized = $this->getSerializer()->normalize($object, null, EndPointParams::buildContext($params));
        $normalized['id'] = $normalized['codigo'];
        $normalized['napidb'] = strtolower($normalized['ssrsDb']);
        $normalized['ssrsDb'] = $normalized['codigo'] = [];
        $normalized = array_filter($normalized, function ($property) {
            return !is_array($property);
        });
        return $this->traitMigrate($normalized, $params);
    }
}