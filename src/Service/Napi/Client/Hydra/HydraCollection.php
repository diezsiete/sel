<?php


namespace App\Service\Napi\Client\Hydra;

use App\Service\Napi\Client\NapiClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HydraCollection implements \Iterator, \Countable
{
    /**
     * @var null|HydraCollectionPage
     */
    protected $currentResponse;

    protected $currentUrl;

    private $client;

    private $internalCounter = 0;
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    private $class;

    /**
     * HydraCollection constructor.
     * @param NapiClient $client
     * @param HydraCollectionPage $currentResponse
     * @param $class
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct($client, $currentResponse, $class, DenormalizerInterface $denormalizer)
    {
        $this->client = $client;
        $this->currentResponse = $currentResponse;
        $this->denormalizer = $denormalizer;
        $this->class = $class;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->currentUrl = $this->currentResponse->getFirstUrl();
        $this->currentResponse->getIterator()->rewind();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        if(!$this->currentResponse->getIterator()->valid()) {
            $this->currentUrl = $this->currentResponse->getNextUrl();
            if($this->currentUrl) {
                $this->currentResponse = new HydraCollectionPage($this->client->get($this->currentUrl));
                return $this->currentResponse->getIterator()->valid();
            }
            return false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $data = $this->currentResponse->getIterator()->current();
        return $this->denormalizer->denormalize($data, $this->class);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->currentResponse->getIterator()->next();
        $this->internalCounter++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->internalCounter;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->currentResponse->getTotalItems();
    }
}