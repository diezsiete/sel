<?php


namespace App\Service\Configuracion\Novasoft;


class NovasoftApiConfiguracion
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var string[]
     */
    private $db;

    public function __construct($config, $empresaConfig)
    {
        $this->url = $config['url'];
        $this->db = $empresaConfig['db'];
    }

    /**
     * @return string[]
     */
    public function getDb(): array
    {
        return $this->db;
    }

    public function getDbPrincipal()
    {
        return $this->db[0];
    }
}