<?php


namespace App\Service\Scraper\Response;


use Symfony\Contracts\EventDispatcher\Event;

class ScraperStreamResponseEvent extends Event
{
    /**
     * @var string
     */
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }


}