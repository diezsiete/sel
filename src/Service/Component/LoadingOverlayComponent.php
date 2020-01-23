<?php


namespace App\Service\Component;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoadingOverlayComponent
{
    const SESSION_NAME = 'loading-overlay';
    private $enabled = false;
    private $options = [];
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $session = $this->session->get(self::SESSION_NAME, false);
        if($session) {
            if(is_array($session)) {
                $this->enabled = true;
                $this->options = $session;
            } else {
                $this->enabled = $session;
            }
        }
    }

    /**
     * @return LoadingOverlayComponent
     */
    public function enable()
    {
        $this->enabled = true;
        $this->session->set(self::SESSION_NAME, true);
        return $this;
    }

    public function useCallbackRoute($route)
    {
        $this->enable()->options['callbackRoute'] = $route;
        $this->session->set(self::SESSION_NAME, $this->options);
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function encodeOptions()
    {
        $encode = json_encode($this->getOptions());
        return $encode;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function clearSession()
    {
        $this->session->remove(self::SESSION_NAME);
    }
}