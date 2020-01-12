<?php


namespace App\Command\Helpers;


use Psr\Log\LoggerInterface;
use App\Helper\Loggable as HelperLoggable;

trait Loggable
{
    use HelperLoggable;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}