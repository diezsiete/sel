<?php
/**
 * Helps to make the transition from a SSRS Record to a object
 * User: guerrerojosedario
 * Date: 2018/08/22
 * Time: 8:48 PM
 */

namespace App\Service\Novasoft\Report\Mapper;


abstract class Mapper
{
    protected $map;

    protected $targetObject;

    /**
     * @var DataFilter
     */
    protected $filter;

    abstract protected function instanceTargetObject();
    abstract protected function defineMap(): array;


    public function __construct(DataFilter $filter)
    {
        $this->map = $this->defineMap();
        $this->targetObject = $this->instanceTargetObject();
        $this->filter = $filter;
    }

    public function __set($name, $value)
    {
        if(isset($this->map[$name])) {
            $entityAttribute = is_array($this->map[$name]) ? $name : $this->map[$name];

            $set_method = "set" . ucfirst($entityAttribute);
            if(method_exists($this, $set_method)) {
                $this->$set_method($value, $name);
            } else if($targetObject = $this->getTargetObject()) {
                $targetObject->$set_method($value);
            }
        }
    }

    public function addMappedObject(&$objects)
    {
        $objects[] = $this->targetObject;
        $this->targetObject = $this->instanceTargetObject();
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    public function getTargetObject()
    {
        return $this->targetObject;
    }
}