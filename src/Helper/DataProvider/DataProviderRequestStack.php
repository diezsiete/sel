<?php


namespace App\Helper\DataProvider;


use Symfony\Component\HttpFoundation\RequestStack;

trait DataProviderRequestStack
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @required
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function getRouteParameter($name)
    {
        return $this->requestStack->getMasterRequest()->attributes->get($name);
    }
}