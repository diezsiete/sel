<?php


namespace App\DataTable\Column\ButtonColumn;


class ButtonTypeModalBorrar extends ButtonType
{

    public function __construct(string $routeName, array $routeParams = [], $icon = 'far fa-trash-alt')
    {
        parent::__construct($icon);
        $this->setAttr([
            'class' => 'btn',
            'data-toggle' => "modal",
            'data-target' => "#modal-borrar",
            'data-delete-url' => new ButtonAttrRoute($routeName, $routeParams)
        ]);
    }

    public function render($value, $context): string
    {
        $attr = $this->renderAttr($value, $context);
        return sprintf('<a %s>%s</a>', $attr, $this->renderIcon());
    }
}