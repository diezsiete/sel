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
        $class = is_object($object) ? get_class($object) : $object;
        $classNameClean = preg_replace('/.+\\\\(\w+)$/', '$1', $class);
        return $this->varchar->toSnakeCase($classNameClean, $glue);
    }
}