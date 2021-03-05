<?php

namespace Sel\RemoteBundle\Api;

use Sel\RemoteBundle\Api\Migrate\Autoliquidacion;
use Sel\RemoteBundle\Api\Migrate\Convenio;
use Sel\RemoteBundle\Api\Migrate\Hv;
use Sel\RemoteBundle\Api\Migrate\Progreso;
use Sel\RemoteBundle\Api\Migrate\Representante;
use Sel\RemoteBundle\Api\Migrate\Usuario;
use Sel\RemoteBundle\Helper\Api\ApiPath;

/**
 * @property Autoliquidacion autoliquidacion
 * @property Convenio convenio
 * @property Hv hv
 * @property Progreso progreso
 * @property Representante representante
 * @property Usuario usuario
 */
class Migrate extends ApiPath
{
}