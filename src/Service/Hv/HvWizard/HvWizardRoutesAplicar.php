<?php


namespace App\Service\Hv\HvWizard;


use App\Service\Configuracion\Configuracion;
use Symfony\Component\HttpFoundation\Request;

class HvWizardRoutesAplicar extends HvWizardRoutes
{
    const ROUTE_APLICAR_KEY = 'aplicar';

    private $slug;
    private $route;

    private $lastRouteIsAplicar = false;
    /**
     * @var string
     */
    private $wizard;

    public function __construct(Configuracion $configuracion, $route, $slug, $wizard = 'registro')
    {
        $this->slug = $slug;
        $this->route = $route;
        $this->wizard = $wizard;
        parent::__construct($configuracion);
    }

    protected function initRoutes()
    {
        parent::initRoutes();
        $this->routes = $this->formatRoutes($this->routes);
    }

    public function getCurrentStepByRoute(Request $request)
    {
        $routeStep = $request->get('_route_params')['step'];
        for($i = 0; $i < count($this->routes); $i++) {
            if($this->routes[$i]->parameters['step'] === $routeStep) {
                return $i;
            }
        }
        return 0;
    }

    public function setRoutes($routes)
    {
        $this->routes = $this->formatRoutes($routes);
    }

    public function removeCuentaPorAplicar()
    {
        $this->lastRouteIsAplicar = true;
    }

    private function formatRoutes($routes)
    {
        $countRoutes = count($routes);
        for ($i = 0; $i < $countRoutes; $i++) {
            $route = $routes[$i];
            $wizard = $this->wizard;
            $step = $route->key;

            if($this->lastRouteIsAplicar && $i + 1 === $countRoutes) {
                $route->key = self::ROUTE_APLICAR_KEY;
                $route->titulo = 'Aplicar';
                $route->valid = false;

                $wizard = null;
                $step = 'basicos';
            }

            $route->route = $this->route;
            $route->parameters = [
                'slug' => $this->slug,
                'wizard' => $wizard,
                'step' => $step,
            ];
        }
        return $routes;
    }
}