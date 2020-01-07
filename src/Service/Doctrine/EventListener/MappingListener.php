<?php


namespace App\Service\Doctrine\EventListener;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        //dump("CALLED");
    }
}