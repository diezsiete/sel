<?php


namespace App\Service\Hv\HvWizard;

use App\Service\Configuracion\Configuracion;
use App\Service\Hv\HvResolver;
use App\Service\Hv\HvValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HvWizardFactory
{
    public function __invoke(HvResolver $hvResolver, SessionInterface $session, RequestStack $requestStack,
                             EventDispatcherInterface $eventDispatcher, Configuracion $configuracion, HvValidator $hvValidator)
    {
        $hvWizard = new HvWizard($session);
        $routesWizard = null;
        if($requestStack->getMasterRequest()->attributes->get('_route') === 'vacante_aplicar' && !$requestStack->getMasterRequest()->isXmlHttpRequest()) {
            if($hvResolver->getUsuario() || $requestStack->getMasterRequest()->attributes->get('_route_params')['wizard']) {
                $hv = $hvResolver->getHv();
                $wizard = $hv && $hv->getUsuario() && $hv->getUsuario()->getId() ? 'completar' : 'registro';
                $vacante = $requestStack->getMasterRequest()->attributes->get('vacante');
                $routesWizard = new HvWizardRoutesAplicar($configuracion, 'vacante_aplicar', $vacante->getSlug(), $wizard);

                if ($wizard === 'completar') {
                    $routesWizard->removeCuentaPorAplicar();
                    $routesInvalidas = $hvValidator->validateWizardRoutes($hvResolver->getHv());
                    $routesWizard->setRoutes($routesInvalidas);
                } else {
                    $hvWizard->useSessionValidation();
                }
            }
        } else {
            $routesWizard = new HvWizardRoutes($configuracion);
            $hvWizard->useSessionValidation();
        }
        if($routesWizard){
            $hvWizard->init($routesWizard, $hvResolver, $hvValidator, $requestStack);
        }
        return $hvWizard;
    }
}