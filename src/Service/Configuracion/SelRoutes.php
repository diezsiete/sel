<?php


namespace App\Service\Configuracion;


class SelRoutes
{
    public $ignore = [];

    public function __construct($data)
    {
        $this->ignore = isset($data['ignore']) ? $data['ignore'] : [];
    }
}