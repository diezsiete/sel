<?php


namespace App\DataTable\Column\ButtonColumn;


use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * TODO se podria mejorar creando uno solo por datatable
 * Class DatatablePropertyAccessor
 * @package App\DataTable\Column\ButtonColumn
 */
class DatatablePropertyAccessor
{
    /**
     * @var string
     */
    private $propertyPath;

    private $propertyAccessor = null;

    /**
     * DatatablePropertyAccessor constructor.
     * @param string $propertyPath
     */
    public function __construct($propertyPath)
    {
        $this->propertyPath = $propertyPath;
    }

    /**
     * @param array|object $objectOrArray
     * @return mixed
     */
    public function getValue($objectOrArray)
    {
        return $this->getPropertyAccessor()->getValue($objectOrArray, $this->propertyPath);
    }

    /**
     * @return PropertyAccessor
     */
    private function getPropertyAccessor()
    {
        if(!$this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }
        return $this->propertyAccessor;
    }
}