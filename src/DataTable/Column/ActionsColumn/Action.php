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
        if (!$this->addAttribute('text', $attributes, $value, $context)) {
            $this->addAttribute('icon', $attributes, $value, $context);
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

        $this->addAttribute('class', $attributes, $value, $context);


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
        if($attributes) {
            $attributes += $this->transformDataOptions($value, $context);
        }

        return $attributes;
    }



    protected function addAttribute($name, &$attributes, $value = null, $context = null)
    {
        if(isset($this->options[$name])) {
            if(is_callable($this->options[$name])) {
                $value = call_user_func($this->options[$name], $value, $context);
            } else {
                $value = $this->getOption($name, $value, $context);
            }
            $attributes[$name] = $value;
            return true;
        }
        return false;
    }

    protected function getOption($name, $value = null, $context = null)
    {
        if (preg_match('/(\[.*\])/', $this->options[$name])) {
            return $this->propertyAccessor->getValue($context, $this->options[$name]);
        }
        else if (preg_match('/(.*)\.(.*)/', $this->options[$name], $matches)) {
            $propertyPath = !$matches[1] ? "$matches[2]" : $this->options[$name];
            return $this->propertyAccessor->getValue($context, $propertyPath);
        }
        return $this->options[$name];
    }

    protected function transformDataOptions($value = null, $context = null)
    {
        $attributes = [];
        foreach($this->options as $optionName => $optionValue) {
            if(preg_match('/^data-.*/', $optionName)) {
                $attributes[$optionName] = $this->getOption($optionName, $value, $context);
            }
        }
        return $attributes;
    }


}