<?php


namespace App\Service\Napi\Client;

use App\Service\Napi\Client\Hydra\HydraCollection;
use App\Service\Napi\Client\Hydra\HydraCollectionMultiple;
use App\Service\Napi\Client\Hydra\HydraCollectionPage;

/**
 * Class NapiClientCollectionOperation
 * @package App\Service\Novasoft\Api\Client
 * @method HydraCollection get(...$args)
 */
class NapiClientCollectionOperation extends NapiClientOperation
{
    protected $dbs = [];

    protected function _get($path, $args)
    {
        $object = null;
        if($this->dbs) {
            $object = new HydraCollectionMultiple($this->napiClient, $this->class, $this->denormalizer);
            $hasResponse = false;
            foreach($this->dbs as $db) {
                if ($data = $this->napiClient->get($path, ['parameters' => $args], $db)) {
                    $object->addInitialResponse($db, new HydraCollectionPage($data));
                    $hasResponse = true;
                }
            }
            if(!$hasResponse) {
                $object = null;
            }
        } else if ($data = $this->napiClient->get($path, ['parameters' => $args])) {
            $object = new HydraCollection(
                $this->napiClient,
                new HydraCollectionPage($data),
                $this->class,
                $this->denormalizer
            );
        }
        return $object;
    }

    public function addDb($db)
    {
        $this->dbs[] = $db;
        return $this;
    }
}