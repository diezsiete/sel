<?php


namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class VacanteAplicarAuthenticator extends LoginFormAuthenticator
{
    /**
     * @var Request
     */
    private $request;


    /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $this->request = $request;
        parent::start($request, $authException);
    }

    /**
     * Return the URL to the login page.
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('vacante_aplicar', ['slug' => $this->request->attributes->get('slug')]);
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        $supports = 'vacante_aplicar' === $request->attributes->get('_route')
            && !$request->attributes->get('_route_params')['wizard']
            && $request->isMethod('POST');
        return $supports;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->request = $request;
        return parent::onAuthenticationFailure($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('vacante_aplicar', ['slug' => $request->attributes->get('slug')])
        );
    }
}