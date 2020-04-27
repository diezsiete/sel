<?php


namespace App\Service\Napi\Client;

use App\Service\Napi\Client\Hydra\HydraCollection;
use App\Service\Napi\Client\Hydra\HydraCollectionPage;

/**
 * Class NapiClientCollectionOperation
 * @package App\Service\Novasoft\Api\Client
 * @method HydraCollection get(...$args)
 */
class NapiClientCollectionOperation extends NapiClientOperation
{
    protected function _get($path, $args)
    {
        $object = null;
        if ($data = $this->napiClient->get($path, ['parameters' => $args])) {
            $object = new HydraCollection(
                $this->napiClient,
                new HydraCollectionPage($data),
                $this->class,
                $this->denormalizer
            );
        }
        return $object;
    }
}