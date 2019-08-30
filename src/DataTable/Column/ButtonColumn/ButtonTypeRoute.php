<?php


namespace App\DataTable\Column\ButtonColumn;


use Symfony\Component\Routing\RouterInterface;

class ButtonTypeRoute extends ButtonType
{
    /**
     * @var ButtonAttrRoute
     */
    protected $attrRoute;

    protected $target = null;

    public function __construct(string $routeName, array $routeParams = [], string $icon = null, string $target = null)
    {
        $this->attrRoute = new ButtonAttrRoute($routeName, $routeParams);
        $this->target = $target;
        parent::__construct($icon);
    }

    public function render($value, $context): string
    {
        $route = $this->generateRoute($value, $context);
        $buttonText = "undefined";
        $tag = "a";
        $target = "";
        if($buttonIcon = $this->icon) {
            $buttonText = "<i class='$buttonIcon'></i>";
        }
        if($route) {
            $route = 'href="' . $route . '"';
            $target = $this->target ? " target='$this->target'" : "";
        } else {
            $tag = 'span';
            $route = 'class="disabled"';
        }

        return sprintf('<%s %s %s>%s</%s>',$tag, $route, $target, $buttonText, $tag);
    }

    protected function generateRoute($value, $context)
    {
        if($value) {
            return $this->attrRoute->setRouter($this->column->getRouter())->render($value, $context);
        }
        return null;
    }
}