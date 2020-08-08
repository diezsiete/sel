<?php

namespace App\Event\EventSubscriber\Api;

use App\Entity\Hv\Adjunto;

class ResolveAdjuntoContentUrlSubscriber extends ResolveContentUrlAttributeSubscriber
{

    protected function getResourceClass(): string
    {
        return Adjunto::class;
    }
}