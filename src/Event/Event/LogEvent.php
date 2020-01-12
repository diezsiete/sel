<?php


namespace App\Event\Event;


use Symfony\Contracts\EventDispatcher\Event;

abstract class LogEvent extends Event
{
    /**
     * @var string
     */
    protected $level;
    /**
     * @var string
     */
    protected $message;
    /**
     * @var array
     */
    protected $context;


    public static function emergency($message, array $context = [])
    {
        return new static('emergency', $message, $context);
    }

    public static function alert($message, array $context = [])
    {
        return new static('alert', $message, $context);
    }

    public static function critical($message, array $context = [])
    {
        return new static('critical', $message, $context);
    }

    public static function error($message, array $context = [])
    {
        return new static('error', $message, $context);
    }

    public static function warning($message, array $context = [])
    {
        return new static('warning', $message, $context);
    }

    public static function notice($message, array $context = [])
    {
        return new static('notice', $message, $context);
    }

    public static function info($message, $context = [])
    {
        return new static('info', $message, $context);
    }

    public static function debug($message, $context = [])
    {
        return new static('debug', $message, $context);
    }


    protected function __construct($level, $message, $context = [])
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}