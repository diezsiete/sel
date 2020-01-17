<?php


namespace App\DataTable;


use Omines\DataTablesBundle\Adapter\Doctrine\Event\ORMAdapterQueryEvent;

interface PreQueryListenerInterface
{
    public function preQueryListener(ORMAdapterQueryEvent $event);
}