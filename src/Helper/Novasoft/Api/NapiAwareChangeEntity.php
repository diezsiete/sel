<?php

namespace App\Helper\Novasoft\Api;

trait NapiAwareChangeEntity
{
    private $napiChanged = false;

    protected function set($property, $value)
    {
        if ($value !== $this->$property) {
            $this->$property = $value;
            $this->napiChanged = true;
        }
        return $this;
    }

    public function isNapiChanged(): bool
    {
        return $this->napiChanged;
    }
}