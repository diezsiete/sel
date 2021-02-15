<?php

namespace Sel\RemoteBundle\Api;

use Sel\RemoteBundle\Api\Migrate\Autoliquidacion;
use Sel\RemoteBundle\Api\Migrate\Convenio;
use Sel\RemoteBundle\Api\Migrate\Progreso;
use Sel\RemoteBundle\Api\Migrate\Usuario;
use Sel\RemoteBundle\Helper\Api\ApiPath;

/**
 * @property Usuario usuario
 * @property Convenio convenio
 * @property Autoliquidacion autoliquidacion
 * @property Progreso progreso
 */
class Migrate extends ApiPath
{
}