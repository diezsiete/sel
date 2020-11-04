<?php

namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @property Servicios servicios
 */
class Halcon
{
    /**
     * @var ParameterBagInterface
     */
    private $params;
    /**
     * @var Servicios
     */
    private $servicios;
    /**
     * @var Varchar
     */
    private $varcharUtil;

    public function __construct(ParameterBagInterface $param, Varchar $varcharUtil)
    {
        $this->params = $param;
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'servicios':
                if($this->servicios === null) {
                    $this->servicios = new Servicios($this->params->get('halcon')['servicios'], $this->varcharUtil);
                }
                return $this->servicios;
        }
        throw new \Exception("propiedad '$name' no existe");
    }
}
