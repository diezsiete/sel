<?php


namespace App\Event\Event\ServicioEmpleados\Report\Importer;


use Symfony\Contracts\EventDispatcher\Event;

class ImportEvent extends Event
{
    public $entity;
    public $entityClass;

    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->entityClass = get_class($entity);
    }
}