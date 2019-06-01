<?php

namespace App\Twig;

use Knp\Menu\Twig\MenuExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SelExtension extends AbstractExtension
{

    /**
     * @var MenuExtension
     */
    private $menuExtension;
    /**
     * @var RequestStack
     */
    private $request;

    public function __construct(RequestStack $request, MenuExtension $menuExtension)
    {
        // $this->menuExtension = $menuExtension;
        $this->request = $request;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            // new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('page_header', [$this, 'pageHeader']),
        ];
    }

    public function pageHeader($menu)
    {
        $header = $this->request->getCurrentRequest()->get('header');
        return $header;
//        return $header ?? $this->menuExtension->getCurrentItem($menu)->getName();
    }
}
