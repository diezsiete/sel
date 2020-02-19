<?php
// based on https://symfony.com/doc/current/event_dispatcher/before_after_filters.html#token-validation-example

namespace App\Event\EventSubscriber;


use App\Controller\ActionBasedOnRole;
use App\Service\Utils\Varchar;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class ActionBasedOnRoleSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var Varchar
     */
    private $stringUtils;

    public function __construct(Security $security, Varchar $stringUtils)
    {
        $this->security = $security;
        $this->stringUtils = $stringUtils;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if ($controller instanceof ActionBasedOnRole && $this->security->getUser()) {
            foreach($this->security->getUser()->getRoles() as $rol) {
                $method = $this->stringUtils->camelize(strtolower(str_replace('ROLE_', '', $rol)));
                if(method_exists($controller, $method)) {
                    $event->setController([$controller, $method]);
                    $event->getRequest()->attributes->set('_controller', get_class($controller) . '::' . $method);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}