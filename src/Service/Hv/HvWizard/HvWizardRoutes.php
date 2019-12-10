<?php


namespace App\Service\Hv\HvWizard;

use App\Service\Configuracion\Configuracion;
use Symfony\Component\HttpFoundation\Request;

class HvWizardRoutes
{
    /**
     * @var HvWizardRoute[]
     */
    protected $routes;
    /**
     * @var Configuracion
     */
    protected $configuracion;

    /**
     * @var string
     */
    protected $wizard = 'registro';

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
        $this->initRoutes();
    }

    public function getCurrentStepByRoute(Request $request)
    {
        $requestRoute = $request->get('_route');
        for($i = 0; $i < count($this->routes); $i++) {
            if($this->routes[$i]->route === $requestRoute) {
                return $i;
            }
        }
        return 0;
    }

    /**
     * @return HvWizardRoute[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param int $step
     * @return HvWizardRoute|null
     */
    public function getByStep(int $step)
    {
        return $this->routes[$step] ?? null;
    }

    protected function initRoutes()
    {
        $this->routes = $this->configuracion->getHvWizardRoutes();
    }

    /**
     * @return string
     */
    public function getWizard(): string
    {
        return $this->wizard;
    }
}