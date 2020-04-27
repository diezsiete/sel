<?php

namespace App\Helper\Novasoft\Api;

use DateTimeInterface;

/**
 * Trait NapiAwareChangeEntity
 * @package App\Helper\Novasoft\Api
 * @deprecated
 */
trait NapiAwareChangeEntity
{
    private $napiChanged = false;
    private $napiChanges = [];

    protected function set($property, $value)
    {
        if($this->$property instanceof DateTimeInterface || $value instanceof DateTimeInterface) {
            $changed = $this->dateTimeInterfaceChanged($this->$property, $value);
        } else {
            $changed = $value !== $this->$property;
        }
        if ($changed) {
            $this->napiChanges[$property] = [$this->$property, $value];
            $this->$property = $value;
            $this->napiChanged = true;
        }
        return $this;
    }

    public function isNapiChanged(): bool
    {
        return $this->napiChanged;
    }

    private function dateTimeInterfaceChanged(?DateTimeInterface $oldValue, ?DateTimeInterface $newValue): bool
    {
        if(!$oldValue) {
            return (bool)$newValue;
        }
        if(!$newValue) {
            return (bool)$oldValue;
        }
        return $oldValue->format('Y-m-d') !== $newValue->format('Y-m-d');
    }
}