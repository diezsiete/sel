<?php


namespace App\Service\NovasoftSsrs\Mapper;


class GenericMapper extends Mapper
{

    protected function defineTargetClass()
    {
        $this->targetClass = GenericEntity::class;
    }

    protected function defineMap()
    {
        $this->map = [];
    }

    public function __set($name, $value)
    {
        $set_method = "set_$name";
        $this->targetObject->$set_method($value);
    }

}