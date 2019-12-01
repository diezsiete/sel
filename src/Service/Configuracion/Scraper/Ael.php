<?php


namespace App\Service\Configuracion\Scraper;


class Ael
{
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $empleador;

    public function __construct($config)
    {
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->empleador = $config['empleador'];
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmpleador(): string
    {
        return $this->empleador;
    }
}