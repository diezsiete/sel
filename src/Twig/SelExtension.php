<?php

namespace App\Twig;

use App\Service\Component\LoadingOverlayComponent;
use App\Service\Configuracion\Configuracion;
use App\Service\Utils;
use DateTime;
use Knp\Menu\Twig\MenuExtension;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SelExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var MenuExtension
     */
    private $menuExtension;
    /**
     * @var RequestStack
     */
    private $request;


    public function __construct(ContainerInterface $container)
    {
        // $this->menuExtension = $menuExtension;
        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('mes', [$this, 'mesFilter'])
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            // new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('master_request', [$this, 'masterRequest']),
            new TwigFunction('is_sel', [$this, 'isSel'])
        ];
    }

    public function masterRequest()
    {
        return $this->container->get(RequestStack::class)->getMasterRequest();
    }

    public function isSel($except = [])
    {
        $except = is_array($except) ? $except : [$except];

        $route = $this->container->get(RequestStack::class)->getCurrentRequest()->attributes->get('_route');

        $isSel = !$except || !in_array($route, $except)
            ? !in_array($route, $this->container->get(Configuracion::class)->getSelRoutes()->ignore)
            : false;
        return $isSel;
    }

    /**
     * @param int|DateTime $n
     * @return array|mixed
     */
    public function mesFilter($n)
    {
        if(is_object($n)) {
            $n = $n->format('m');
        }
        return $this->container->get(Utils::class)->meses($n - 1);
    }





    public static function getSubscribedServices()
    {
        return [
            RequestStack::class,
            Utils::class,
            Configuracion::class,
            LoadingOverlayComponent::class,
        ];
    }
}
