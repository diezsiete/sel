<?php


namespace App\Service\Configuracion\Halcon;


use App\Service\Utils\Varchar;

abstract class ServicioEndPoints
{
    /**
     * @var string
     */
    protected $url;
    /**
     * @var Varchar
     */
    protected $varcharUtil;

    protected $nameGlue = '-';

    public function __construct(string $url, Varchar $varcharUtil)
    {
        $this->url = $url;
        $this->varcharUtil = $varcharUtil;
    }

    public function __get($name)
    {
        switch ($name) {
            default:
                return $this->url . '/' . $this->varcharUtil->toSnakeCase($name, $this->nameGlue);
        }
    }
}