<?php


namespace App\DataTable\Column\ActionsColumn;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

abstract class Action
{
    /**
     * @var PropertyAccessorInterface
     */
    protected $propertyAccessor;


    protected $options = [];


    public function __construct(PropertyAccessorInterface $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function transform($value = null, $context = null)
    {
        $attributes = [];
        if (isset($this->options['text'])) {
            $attributes['text'] = $this->getOption('text', $value, $context);
        } else if (isset($this->options['icon'])) {
            $attributes['icon'] = $this->options['icon'];
        }

        if (!isset($attributes['disabled']) && isset($this->options['target'])) {
            $attributes['target'] = $this->options['target'];
        }

        if (isset($this->options['tooltip'])) {
            $attributes['data-toggle'] = "tooltip";
            $attributes['title'] = $this->options['tooltip'];
            $attributes['data-placement'] = 'top';
        }

        if (isset($this->options["tag"])) {
            $attributes["tag"] = $this->options["tag"];
        }

        $data = $this->options['data'] ?? null;
        if (is_callable($data)) {
            $value = call_user_func($data, $context, $value);
            if (!$value) {
                $attributes = false;
            }
            if ($value === 'disabled') {
                $attributes['disabled'] = true;
            }
        } elseif (null === $value) {
            $attributes = false;
        }


        return $attributes;
    }


    protected function getOption($name, $value = null, $context = null)
    {
        if (preg_match('/(\[.*\])|(.*\..*)/', $this->options[$name])) {
            return $this->propertyAccessor->getValue($context, $this->options[$name]);
        }
        return $this->options[$name];
    }
}