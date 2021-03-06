<?php
/**
 * Helps to make the transition from a SSRS Record to a object
 * User: guerrerojosedario
 * Date: 2018/08/22
 * Time: 8:48 PM
 */

namespace App\Service\NovasoftSsrs\Mapper;



use App\Service\NovasoftSsrs\DataFilter;

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


    public function __construct()
    {
        $this->map = $this->defineMap();

        $this->targetObject = $this->instanceTargetObject();
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
        $this->targetObject = $this->instanceTargetObject();
    }

    public function setFilter(DataFilter $filter)
    {
        $this->filter = $filter;
        return $this;
    }
}