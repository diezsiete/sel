<?php


namespace App\DataTable\Column\ButtonColumn;


use Symfony\Component\Routing\RouterInterface;

class ButtonTypeRoute extends ButtonType
{
    /**
     * @var ButtonAttrRoute
     */
    protected $attrRoute;

    public function __construct(string $routeName, array $routeParams = [], string $icon = null)
    {
        $this->attrRoute = new ButtonAttrRoute($routeName, $routeParams);
        parent::__construct($icon);
    }

    public function render($value, $context): string
    {
        $route = $this->generateRoute($value, $context);
        $buttonText = "undefined";
        if($buttonIcon = $this->icon) {
            $buttonText = "<i class='$buttonIcon'></i>";
        }
        return sprintf('<a href="%s">%s</a>', $route, $buttonText);
    }

    protected function generateRoute($value, $context)
    {
        return $this->attrRoute->setRouter($this->column->getRouter())->render($value, $context);
    }
}