<?php


namespace App\DataTable\Column\ActionsColumn;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\RouterInterface;

class ActionRoute extends Action
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    private $route = null;

    private $routeParameters = [];


    public function __construct(RouterInterface $router, PropertyAccessorInterface $propertyAccessor)
    {
        $this->router = $router;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function setOptions($options)
    {
        parent::setOptions($options);
        $this->route = $this->options['route'][0];
        if(isset($this->options['route'][1])) {
            $this->routeParameters = $this->options['route'][1];
        }
    }

    public function transform($value = null, $context = null)
    {
        $attributes = parent::transform($value, $context);
        if($attributes && !isset($attributes['disabled'])) {
            $attributes['href'] = $this->buildRoute($value, $context);
        }
        return $attributes;
    }


    private function buildRoute($value, $context)
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
        return $this->router->generate($this->route, $routeParams);
    }
    
}