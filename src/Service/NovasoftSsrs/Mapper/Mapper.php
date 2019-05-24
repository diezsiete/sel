<?php
/**
 * Helps to make the transition from a SSRS Record to a object
 * User: guerrerojosedario
 * Date: 2018/08/22
 * Time: 8:48 PM
 */

namespace App\Service\NovasoftSsrs\Mapper;



abstract class Mapper
{
    protected $map;

    protected $targetClass;

    protected $targetObject;

    abstract protected function defineTargetClass();
    abstract protected function defineMap();


    public function __construct()
    {
        $this->defineTargetClass();
        $this->defineMap();

        $this->targetObject = new $this->targetClass();
    }

    public function __set($name, $value)
    {
        if(isset($this->map[$name])) {
            $set_method = "set" . ucfirst($this->map[$name]);
            if(method_exists($this, $set_method)) {
                $this->$set_method($value);
            } else {
                $this->targetObject->$set_method($value);
            }
        }
    }

    public function addMappedObject(&$objects)
    {
        $objects[] = $this->targetObject;
        $this->targetObject = new $this->targetClass();
    }
}