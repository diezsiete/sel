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

    /**
     * @var Route
     */
    private $route = null;


    public function __construct(RouterInterface $router, PropertyAccessorInterface $propertyAccessor)
    {
        $this->router = $router;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function setOptions($options)
    {
        parent::setOptions($options);
        $this->route = new Route($this->options['route'], $this->propertyAccessor);
    }

    public function transform($value = null, $context = null)
    {
        $attributes = parent::transform($value, $context);
        if($attributes && !isset($attributes['disabled'])) {
            $attributes['href'] = $this->router->generate($this->route->getRoute(), $this->route->getParameters($context, $value));
        }
        return $attributes;
    }
    
}