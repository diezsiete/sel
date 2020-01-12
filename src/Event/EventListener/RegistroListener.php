<?php


namespace App\Event\EventListener;


use App\Entity\Main\Usuario;
use App\Service\Hv\HvResolver;
use App\Service\Hv\HvWizard\HvWizard;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegistroListener
{
    private $tokenStorage;
    private $router;
    /**
     * @var HvResolver
     */
    private $hvResolver;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(TokenStorageInterface $t, RouterInterface $r, HvResolver $hvResolver, SessionInterface $session, KernelInterface $kernel)
    {
        $this->tokenStorage = $t;
        $this->router = $r;
        $this->hvResolver = $hvResolver;
        $this->session = $session;
        $this->kernel = $kernel;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $currentRoute = $request->attributes->get('_route');

        if(!$event->getRequest()->isXmlHttpRequest()) {
            if ($this->isUserLogged()) {
                if ($currentRoute && preg_match('/registro_/', $currentRoute)) {
                    $response = new RedirectResponse($this->router->generate('hv_datos_basicos'));
                    $event->setResponse($response);
                }
            } else {
                // sub request de controller fragment renderizado en template ignoramos (!$currentRoute)
                if ($currentRoute && !preg_match('/registro_/', $currentRoute) && $currentRoute !== 'vacante_aplicar') {
                    $this->session->remove(HvWizard::NAMESPACE);
                }
            }
        }
    }

    private function isUserLogged()
    {
        $usuario = null;
        if($token = $this->tokenStorage->getToken()) {
            $usuario = $token->getUser();
            if($usuario instanceof Usuario) {
                $this->hvResolver->setUsuario($usuario);
            } else {
                $usuario = null;
            }
        }
        return $usuario ? true : false;
    }
}