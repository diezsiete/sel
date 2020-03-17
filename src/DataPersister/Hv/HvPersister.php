<?php


namespace App\DataPersister\Hv;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Hv\Hv;

class HvPersister implements ContextAwareDataPersisterInterface
{

    /**
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Hv;
    }

    /**
     * @inheritDoc
     */
    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function remove($data, array $context = [])
    {
        // nothing to do
    }
}