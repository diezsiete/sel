<?php


namespace App\DataTable\Column\ActionsColumn;


abstract class Action
{
    protected $options;

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function transform($value = null, $context = null)
    {
        $attributes = [];
        if(isset($this->options['icon'])) {
            $attributes['icon'] = $this->options['icon'];
        }

        if(!isset($attributes['disabled']) && isset($this->options['target'])){
            $attributes['target'] = $this->options['target'];
        }

        if(isset($this->options['tooltip'])) {
            $attributes['data-toggle'] = "tooltip";
            $attributes['title'] = $this->options['tooltip'];
            $attributes['data-placement'] = 'top';
        }

        $data = $this->options['data'] ?? null;
        if (is_callable($data)) {
            $value = call_user_func($data, $context, $value);
            if(!$value) {
                $attributes = false;
            }
            if($value === 'disabled'){
                $attributes['disabled'] = true;
            }
        } elseif (null === $value) {
            $attributes = false;
        }

        return $attributes;
    }
}