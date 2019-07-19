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
        if($buttonIcon = $this->icon) {
            $buttonText = "<i class='$buttonIcon'></i>";
        }
        $target = $this->target ? " target='$this->target'" : "";
        return sprintf('<a href="%s"%s>%s</a>', $route, $target, $buttonText);
    }

    protected function generateRoute($value, $context)
    {
        return $this->attrRoute->setRouter($this->column->getRouter())->render($value, $context);
    }
}