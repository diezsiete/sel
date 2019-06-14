<?php


namespace App\EventListener;


use App\Entity\Usuario;
use App\Service\HvResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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

    public function __construct(TokenStorageInterface $t, RouterInterface $r, HvResolver $hvResolver)
    {
        $this->tokenStorage = $t;
        $this->router = $r;
        $this->hvResolver = $hvResolver;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');
        if(preg_match('/registro_/', $currentRoute)) {
            if($this->isUserLogged() && $this->hvResolver->getHv()) {
                $response = new RedirectResponse($this->router->generate('hv_datos_basicos'));
                $event->setResponse($response);
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