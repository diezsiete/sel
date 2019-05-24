<?php


namespace App\Service\NovasoftSsrs\Mapper;


class GenericMapper extends Mapper
{

    protected function defineTargetClass(): string
    {
        return GenericEntity::class;
    }

    protected function defineMap(): array
    {
        return [];
    }

    public function __set($name, $value)
    {
        $set_method = "set_$name";
        $this->targetObject->$set_method($value);
    }

}