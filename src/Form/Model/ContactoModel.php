<?php


namespace App\Form\Model;

class ContactoModel
{
    public $nombre;

    public $from;

    public $mensaje;

    public $asunto = null;

    public $to;

    public $solicitudServicio;

    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
        return $this;
    }
}