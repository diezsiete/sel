<?php


namespace App\DataTable\Column\ActionsColumn;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Route
{
    private $route = null;

    private $routeParameters = [];
    private $propertyAccessor;

    public function __construct($routeConfig, PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;

        $routeConfig = is_array($routeConfig) ? $routeConfig : [$routeConfig];
        $this->route = $routeConfig[0];
        if(isset($routeConfig[1])) {
            $this->routeParameters = $routeConfig[1];
        }
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParameters($context, $value)
    {
        return $this->buildRouteParams($context, $value);
    }

    /**
     * @param $context
     * @param $value
     * @return array
     */
    private function buildRouteParams($context, $value)
    {
        $routeParams = [];
        foreach($this->routeParameters as $routeParamKey => $routeParamValue) {
            if(is_int($routeParamKey)) {
                $routeParams[$routeParamValue] = $value;
            } else {
                if(preg_match('/"(.+)"/', $routeParamValue, $matches)) {
                    $routeParams[$routeParamKey] = $matches[1];
                } else {
                    $routeParams[$routeParamKey] = $this->propertyAccessor->getValue($context, $routeParamValue);
                }
            }
        }
        return $routeParams;
    }
}