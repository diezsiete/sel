<?php


namespace App\DataTable\Column\ButtonColumn;


class ButtonTypeModalBorrar extends ButtonTypeRoute
{

    public function __construct(string $routeName, array $routeParams = [])
    {
        parent::__construct($routeName, $routeParams, 'far fa-trash-alt');
    }


    public function render($value, $context): string
    {
        $routeBorrar = $this->generateRoute($value, $context);

        return sprintf(
            '<a href="#" data-toggle="modal" data-target="#modal-borrar" data-path="%s" %s><i class="%s"></i></a>',
            $routeBorrar, $this->renderAttr($value, $context), $this->icon);
    }
}