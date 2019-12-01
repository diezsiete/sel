<?php


namespace App\Message\Scraper;


class UpdateResponseReceived
{
    /**
     * @var int
     */
    private $messageId;
    /**
     * @var string
     */
    private $log;
    /**
     * @var bool
     */
    private $success;

    public function __construct(int $messageId, string $log, $success = true)
    {
        $this->messageId = $messageId;
        $this->log = $log;
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}