<?php


namespace App\DataTable\Column\ActionsColumn;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\RouterInterface;

class ActionModal extends Action
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var Route
     */
    private $confirmRoute = null;

    /**
     * @var string
     */
    private $target;

    public function __construct(RouterInterface $router, PropertyAccessorInterface $propertyAccessor)
    {
        $this->router = $router;
        $this->propertyAccessor = $propertyAccessor;
    }

    public function setOptions($options)
    {
        $this->target = $options['modal'];
        parent::setOptions($options);
        if(isset($options['confirm']) && is_array($options['confirm'])) {
            $this->confirmRoute = new Route($options['confirm'], $this->propertyAccessor);
        }
    }

    public function transform($value = null, $context = null)
    {
        $attributes = parent::transform($value, $context);
        if($attributes) {
            $attributes['class'] = 'datatable-modal';
            $attributes['href'] = $this->target;
            if (!isset($attributes['disabled']) && $this->confirmRoute) {
                $attributes['data-confirm'] = $this->router
                    ->generate($this->confirmRoute->getRoute(), $this->confirmRoute->getParameters($context, $value));
            }
        }
        return $attributes;
    }
}