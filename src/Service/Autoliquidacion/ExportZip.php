<?php


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Usuario;

class ExportZip extends Export
{

    public function generate(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null): Export
    {
        // TODO: Implement generate() method.
    }

    public function stream(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null)
    {
        // TODO: Implement stream() method.
    }
}