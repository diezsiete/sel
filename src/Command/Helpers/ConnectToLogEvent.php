<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use App\Event\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait ConnectToLogEvent
{
    /**
     * @BeforeRun
     */
    public function addListener()
    {
        $this->getDispatcher()->addListener($this->getLogEvent(), [$this, 'logEventListener']);
    }

    public function logEventListener(LogEvent $logEvent)
    {
        $this->log($logEvent->getLevel(), $logEvent->getMessage(), $logEvent->getContext());
    }

    abstract protected function getLogEvent();

    /**
     * Dado por TraitableCommand
     * @return EventDispatcherInterface
     */
    abstract protected function getDispatcher();

    /**
     * Dado por Loggable Trait
     * @param $level
     * @param $message
     * @param array $context
     * @return mixed
     */
    abstract public function log($level, $message, array $context = array());
}