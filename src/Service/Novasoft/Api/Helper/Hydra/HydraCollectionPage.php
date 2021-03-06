<?php

namespace App\Service\Novasoft\Api\Helper\Hydra;


use Exception;
use Traversable;

/**
 * Class HydraCollectionPage
 * @package App\Service\Novasoft\Api\Helper\Hydra
 * @deprecated
 */
class HydraCollectionPage implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $response;

    private $iterator;

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->iterator = new \ArrayIterator($response['hydra:member']);
    }

    public function getNextUrl(): ?string
    {
        return $this->response['hydra:view']['hydra:next'] ?? null ;
    }

    public function getFirstUrl(): ?string
    {
        return $this->response['hydra:view']['hydra:first'] ?? null;
    }

    public function getCurrentUrl(): string
    {
        return $this->response['hydra:view']['@id'];
    }

    public function getTotalItems()
    {
        return $this->response['hydra:totalItems'];
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->iterator;
    }


}