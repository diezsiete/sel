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
            new TwigFunction('is_sel', [$this, 'isSel']),

            new TwigFunction('loading_overlay_body_class', [$this, 'loadingOverlayBodyClass']),
            new TwigFunction('loading_overlay_body_options', [$this, 'loadingOverlayBodyOptions'], ['is_safe' => ['html']]),
            new TwigFunction('loading_overlay', [$this, 'loadingOverlayTemplate'], ['is_safe' => ['html']])
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

        return !$except || !in_array($route, $except)
            ? !in_array($route, $this->container->get(Configuracion::class)->getSelRoutes()->ignore)
            : false;
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

    public function loadingOverlayBodyClass()
    {
        if($this->container->get(LoadingOverlayComponent::class)->isEnabled()) {
            return 'loading-overlay-showing';
        }
        return '';
    }

    public function loadingOverlayBodyOptions()
    {
        $component = $this->container->get(LoadingOverlayComponent::class);
        if($component->isEnabled()) {
            return "data-loading-overlay data-loading-overlay-options='".$component->encodeOptions()."'";
        }
        return '';
    }

    public function loadingOverlayTemplate()
    {
        $component = $this->container->get(LoadingOverlayComponent::class);
        if($component->isEnabled()) {
            $component->clearSession();
            return
                '<div class="loading-overlay">
                    <div class="bounce-loader with-text">
                        <h4>Estamos cargando su información, por favor espere un momento</h4>
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                </div>';
        }
        return '';
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
