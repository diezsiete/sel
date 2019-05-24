<?php

namespace App\Service\NovasoftSsrs\Mapper;


class GenericEntity
{
    public function __call($name, $arguments)
    {
        if(preg_match('/(^set)([A-Z].+)/', $name, $matches)) {
            $attribute = lcfirst($matches[2]);
            $this->$attribute = $arguments[0];
        }
    }
}