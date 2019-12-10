<?php


namespace App\EventListener;


use App\Entity\Usuario;
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
        $currentRoute = $event->getRequest()->attributes->get('_route');
        if(preg_match('/registro_/', $currentRoute)) {
            if($this->isUserLogged() && !$event->getRequest()->isXmlHttpRequest()) {
                $response = new RedirectResponse($this->router->generate('hv_datos_basicos'));
                $event->setResponse($response);
            }
        } else {
            if(!$this->kernel->isDebug()) {
                // TODO no se exactamente para que es esto pero borra la session en medio del registro
                $this->session->remove(HvWizard::NAMESPACE);
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