<?php


namespace App\Service\Napi\Client\Hydra;

use App\Service\Napi\Client\NapiClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class HydraCollectionMultiple implements \Iterator, \Countable
{
    /**
     * @var HydraCollectionPage[]
     */
    protected $currentResponses;

    protected $currentResponseIndex = 0;


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
     * @param $class
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct($client, $class, DenormalizerInterface $denormalizer)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
        $this->class = $class;
    }

    public function addInitialResponse(string $index, HydraCollectionPage $response)
    {
        $this->currentResponses[] = $response;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->currentResponseIndex = 0;
        $this->currentUrl = $this->currentResponses[$this->currentResponseIndex]->getFirstUrl();
        $this->currentResponses[$this->currentResponseIndex]->getIterator()->rewind();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        if(!$this->currentResponses[$this->currentResponseIndex]->getIterator()->valid()) {
            $this->currentUrl = $this->currentResponses[$this->currentResponseIndex]->getNextUrl();
            if($this->currentUrl) {
                $this->currentResponses[$this->currentResponseIndex] = new HydraCollectionPage($this->client->get($this->currentUrl, [], false));
                return $this->currentResponses[$this->currentResponseIndex]->getIterator()->valid();
            }
            $this->currentResponseIndex++;
            return isset($this->currentResponses[$this->currentResponseIndex])
                ? $this->valid()
                : false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        $data = $this->currentResponses[$this->currentResponseIndex]->getIterator()->current();
        return $this->denormalizer->denormalize($data, $this->class);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->currentResponses[$this->currentResponseIndex]->getIterator()->next();
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
        return array_reduce($this->currentResponses, static function ($carry, HydraCollectionPage $response) {
            return $carry + $response->getTotalItems();
        }, 0);
    }
}