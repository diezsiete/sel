<?php


namespace App\Service\Configuracion\Novasoft;


class NovasoftApiConfiguracion
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var string
     */
    public $db;

    public function __construct($config, $empresaConfig)
    {
        $this->url = $config['url'];
        $this->db = $empresaConfig['db'];
    }
}