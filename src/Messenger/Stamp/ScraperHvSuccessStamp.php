<?php


namespace App\Messenger\Stamp;


use Symfony\Component\Messenger\Stamp\StampInterface;

class ScraperHvSuccessStamp implements StampInterface
{
    /**
     * @var string
     */
    private $log;

    public function __construct(string $log)
    {
        $this->log = $log;
    }

    /**
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

}