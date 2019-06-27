<?php


namespace App\Service\Hv\HvWizard;


class HvWizardRoute
{
    public $key;
    public $route;
    public $titulo;
    public $parameters;
    public $valid = false;

    public function __construct($key, $route, $titulo, $params = [])
    {
        $this->key = $key;
        $this->route = $route;
        $this->titulo = $titulo;
        $this->parameters = $params;
    }
}