<?php


namespace App\Messenger;


use Symfony\Component\Messenger\Stamp\StampInterface;

class HvIdStamp implements StampInterface
{
    /**
     * @var int
     */
    private $hvId;

    public function __construct($hvId)
    {
        $this->hvId = $hvId;
    }

    /**
     * @return int
     */
    public function getHvId(): int
    {
        return $this->hvId;
    }


}