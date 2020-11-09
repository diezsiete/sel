<?php


namespace App\Helper\Halcon\Terminal;


class Proceso
{
    /**
     * @var string
     */
    private $comando;
    /**
     * @var string[]
     */
    private $opciones;
    /**
     * @var string|null
     */
    private $cwd;
    /**
     * @var string[]
     */
    private $env;

    /**
     * Proceso constructor.
     * @param $comando
     * @param string[]|string $opciones
     * @param null $cwd
     * @param array $env
     */
    public function __construct($comando, $opciones = [], $cwd = null, $env = [])
    {
        $this->comando = $comando;
        $this->opciones = is_array($opciones) ? $opciones : [$opciones];
        $this->cwd = $cwd;
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getComando()
    {
        return $this->comando;
    }

    /**
     * @param string $opcion
     * @return $this
     */
    public function addOpcion($opcion)
    {
        $this->opciones[] = $opcion;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOpciones(): array
    {
        return $this->opciones;
    }

    /**
     * @param string $cwd
     * @return Proceso
     */
    public function setCwd($cwd): self
    {
        $this->cwd = $cwd;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * @param string $nombre
     * @param string $valor
     * @return $this
     */
    public function addEnv($nombre, $valor)
    {
        $this->env[$nombre] = $valor;
        return $this;
    }

    /**
     * @return array
     */
    public function getEnv(): array
    {
        return $this->env;
    }
}