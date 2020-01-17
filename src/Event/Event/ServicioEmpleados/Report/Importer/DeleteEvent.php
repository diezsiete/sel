<?php


namespace App\Event\Event\ServicioEmpleados\Report\Importer;


use Symfony\Contracts\EventDispatcher\Event;

class DeleteEvent extends Event
{
    public $entityId;
    public $entityClass;

    public function __construct($entityId, $entityClass)
    {
        $this->entityId = $entityId;
        $this->entityClass = $entityClass;
    }
}