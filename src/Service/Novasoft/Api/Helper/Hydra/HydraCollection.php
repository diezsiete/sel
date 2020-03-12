<?php


namespace App\Service\Novasoft\Api\Helper\Hydra;


use App\Service\Novasoft\Api\Client\NovasoftApiClient;

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
     * HydraCollection constructor.
     * @param NovasoftApiClient $client
     * @param HydraCollectionPage $currentResponse
     */
    public function __construct($client, $currentResponse)
    {
        $this->client = $client;
        $this->currentResponse = $currentResponse;
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
                $this->currentResponse = new HydraCollectionPage($this->client->sendGet($this->currentUrl));
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
        return $this->currentResponse->getIterator()->current();
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