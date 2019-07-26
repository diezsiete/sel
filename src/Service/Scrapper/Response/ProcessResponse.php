<?php


namespace App\Service\Scrapper\Response;


use App\Entity\ScrapperProcess;
use App\Entity\Usuario;

class ProcessResponse
{
    private $id;
    private $estado;
    private $porcentaje;

    /**
     * ScrapperResponse constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->estado = $data['estado'];
        $this->porcentaje = $data['porcentaje'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @return int
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * @param Usuario|null $usuario
     * @return ScrapperProcess
     */
    public function getEntity(Usuario $usuario = null)
    {
        return new ScrapperProcess($this->id, $this->estado, $this->porcentaje, $usuario);
    }


}