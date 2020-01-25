<?php


namespace App\Service\Configuracion\ServicioEmpleados;


use DateInterval;

class Report
{
    protected $name;
    protected $refreshIntervalSpec;
    protected $refreshInterval;
    protected $config;

    public function __construct($name, $config)
    {
        $this->name = $name;
        $this->config = $config;
        $this->refreshIntervalSpec = $config['refresh_interval'];
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @return DateInterval
     */
    public function getRefreshInterval()
    {
        if(!$this->refreshInterval) {
            $this->refreshInterval = new DateInterval($this->refreshIntervalSpec);
        }
        return $this->refreshInterval;
    }

    public function getRefreshIntervalSpec()
    {
        return $this->refreshIntervalSpec;
    }
}