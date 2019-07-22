<?php


namespace App\Command\Helpers;



use App\Command\Helpers\ModifyRun\Annotation\ModifyRunBefore;
use App\Command\Helpers\ModifyRun\Event\ModifyRunBeforeEvent;
use Symfony\Component\Console\Exception\RuntimeException;

trait OptionRequired
{
    protected static $optionsRequired = [];

    public function addOptionRequired($name, $shortcut = null, $mode = null, $description = '', $default = null)
    {
        static::$optionsRequired[] = $name;
        return parent::addOption($name, $shortcut, $mode, $description, $default);
    }

    /**
     * @ModifyRunBefore
     */
    public function validateOptionsRequired(ModifyRunBeforeEvent $event)
    {
        foreach(static::$optionsRequired as $optionName) {
            if(!$event->input->getOption($optionName)) {
                throw new RuntimeException(sprintf('The "--%s" option is required.', $optionName));
            }
        }
    }

    /**
     * Obliga a utilizar el trait ModifyRun
     */
    abstract protected function modifyRun();
}