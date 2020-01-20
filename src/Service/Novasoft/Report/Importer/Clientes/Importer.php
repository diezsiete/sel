<?php


namespace App\Service\Novasoft\Report\Importer\Clientes;


abstract class Importer extends \App\Service\Novasoft\Report\Importer\Importer
{
    protected function dispatchDeleteEvent($entity)
    {
        // no events to dispatch
    }

    protected function dispatchImportEvent($entity)
    {
        // no events to dispatch
    }

}