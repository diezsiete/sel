<?php


namespace App\DataTable\Column\ButtonColumn;


abstract class ButtonType
{
    /**
     * @var string|null
     */
    protected $icon;


    protected $attr = [];

    /**
     * @var ButtonColumn
     */
    protected $column;

    /**
     * @param mixed $value
     * @param mixed $context All relevant data of the entire row
     * @return string
     */
    abstract public function render($value, $context): string;


    public function __construct(?string $icon = null)
    {
        $this->icon = $icon;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     * @return ButtonType
     */
    public function setIcon(?string $icon): ButtonType
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @param mixed $attr
     * @return ButtonType
     */
    public function setAttr(array $attr)
    {
        $this->attr = $attr;
        return $this;
    }

    /**
     * @param ButtonColumn $column
     * @return ButtonType
     */
    public function setColumn(ButtonColumn $column): ButtonType
    {
        $this->column = $column;
        return $this;
    }

    protected function renderAttr($value, $context)
    {
        $html = "";
        foreach($this->attr as $attrName => $attrValue) {
            if($attrValue === '$value') {
                $attrValue = $value;
            }else if($attrValue instanceof ButtonAttrRoute) {
                $attrValue = $attrValue->setRouter($this->column->getRouter())->render($value, $context);
            }
            $html .= " $attrName='$attrValue'";
        }
        return $html;
    }


    protected function renderIcon()
    {
        $icon = "undefined";
        if($this->icon){
            $icon = "<i class='$this->icon'></i>";
        }
        return $icon;
    }
}