<?php


namespace App\Service\Configuracion\Halcon;

use App\Service\Utils\Varchar;

/**
 * @property Correo correo
 */
class Servicios
{
    /**
     * @var array
     */
    private $params;

    private $_correo;
    /**
     * @var Varchar
     */
    private $varcharUtil;

    public function __construct(array $params, Varchar $varcharUtil)
    {
        $this->params = $params;
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'correo':
                if($this->_correo === null) {
                    $this->_correo = new Correo($this->params['url'], $this->varcharUtil);
                }
                return $this->_correo;
        }
        throw new \Exception("property '$name' no existe");
    }
}