<?php


namespace App\Service;


use Throwable;

class ExceptionHandler
{
    private $appEnv;

    public function __construct($appEnv)
    {
        $this->appEnv = $appEnv;
    }

    public function handle(Throwable $e): void
    {
        if($this->appEnv === 'dev') {
            throw $e;
        } else {
            //TODO, enviar correo admin
        }
    }
}