<?php


namespace App\Service\Utils;


class Symbol
{
    /**
     * @var Varchar
     */
    private $varchar;

    public function __construct(Varchar $varchar)
    {
        $this->varchar = $varchar;
    }

    /**
     * @param object|string $object
     * @param string $glue
     * @return string
     */
    public function toSnakeCase($object, $glue = '_'): string
    {
        $classNameClean = $this->removeNamespaceFromClassName($object);
        return $this->varchar->toSnakeCase($classNameClean, $glue);
    }

    public function removeNamespaceFromClassName($className)
    {
        $className = is_object($className) ? get_class($className) : $className;
        return preg_replace('/.+\\\\(\w+)$/', '$1', $className);
    }
}