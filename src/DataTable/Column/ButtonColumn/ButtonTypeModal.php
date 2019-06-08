<?php


namespace App\DataTable\Column\ButtonColumn;


class ButtonTypeModal extends ButtonType
{
    /**
     * @var string
     */
    private $target;

    public function __construct(string $target, string $icon = null)
    {
        parent::__construct($icon);
        $this->target = $target;
    }


    public function render($value, $context): string
    {
        $icon = "undefined";
        if($this->icon){
            $icon = "<i class='$this->icon'></i>";
        }
        $attr = $this->renderAttr($value, $context);
        return sprintf('<a href="%s" %s>%s</a>', $this->target, $attr, $icon);
    }
}