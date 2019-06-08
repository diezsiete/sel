<?php


namespace App\DataTable\Column\ButtonColumn;


use Symfony\Component\Routing\RouterInterface;

class ButtonAttrRoute
{
    /**
     * @var string
     */
    private $routeName;
    /**
     * @var array
     */
    private $routeParams;
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(string $routeName, array $routeParams = [])
    {
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
    }

    /**
     * @param RouterInterface $router
     * @return ButtonAttrRoute
     */
    public function setRouter(RouterInterface $router): ButtonAttrRoute
    {
        $this->router = $router;
        return $this;
    }


    public function render($value, $context)
    {
        $routeParams = [];
        foreach($this->routeParams as $routeParamKey => $routeParamValue) {
            if(is_int($routeParamKey)) {
                $routeParams[$routeParamValue] = $value;
            } else {
                // TODO
                throw new \Exception("Parametros mapeados a valor compuesto no soportado aun");
            }
        }
        return $this->router->generate($this->routeName, $routeParams);
    }
}