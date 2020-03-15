<?php


namespace App\Service\Configuracion;


class Ael
{
    /**
     * @var string
     */
    private $url;
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
        $this->url = $config['url'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->empleador = $config['empleador'];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
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