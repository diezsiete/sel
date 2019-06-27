<?php


namespace App\Service\Hv\HvWizard;

use App\Entity\Hv;
use App\Entity\Usuario;
use App\Service\Hv\HvResolver;
use App\Service\Hv\HvValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HvWizard
{
    const NAMESPACE = 'hvwizard';

    const SESSION_ID = self::NAMESPACE . '/hv_id';
    const SESSION_USUARIO = self::NAMESPACE . '/usuario';
    const SESSION_STEP = self::NAMESPACE . '/step';
    const SESSION_STEP_VALID = self::NAMESPACE . '/step_valid';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var HvResolver
     */
    private $hvResolver;

    /**
     * @var int
     */
    private $step;

    /**
     * @var HvWizardRoutes
     */
    private $routes;
    /**
     * @var HvValidator
     */
    private $hvValidator;

    private $sessionValidation = false;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function init(HvWizardRoutes $routes, HvResolver $hvResolver, HvValidator $hvValidator, RequestStack $requestStack)
    {
        $this->routes = $routes;
        $this->hvResolver = $hvResolver;
        $this->hvValidator = $hvValidator;

        $request = $requestStack->getCurrentRequest();
        if($request->isXmlHttpRequest()) {
            $this->step = $this->session->get(self::SESSION_STEP) ?? 0;
        } else {
            $this->step = $this->routes->getCurrentStepByRoute($requestStack->getMasterRequest());
            $this->session->set(self::SESSION_STEP, $this->step);
        }

        if($this->sessionValidation) {
            foreach($this->routes->getRoutes() as $route) {
                $sessionKey = self::SESSION_STEP_VALID . '/' . $route->key;
                if(!$this->session->get($sessionKey)) {
                    $this->session->set($sessionKey, $route->valid);
                } else {
                    $route->valid = $this->session->get($sessionKey);
                }
            }
        }

        return $this;
    }

    public function useSessionValidation()
    {
        $this->sessionValidation = true;
    }

    public function getHv()
    {
        return $this->hvResolver->getHv() ?? new Hv();
    }

    public function getUsuario()
    {
        $usuario = $this->hvResolver->getUsuario();
        if(!$usuario) {
            if (!$usuario = $this->session->get(self::SESSION_USUARIO)) {
                $usuario = new Usuario();
            }
        }
        return $usuario;
    }


    public function addStep()
    {
        $this->step++;
        $this->session->set(self::SESSION_STEP, $this->step);
        return $this;
    }

    public function setHv(Hv $hv)
    {
        $this->session->set(self::SESSION_ID, $hv->getId());
        return $this;
    }

    public function setUsuario(Usuario $usuario)
    {
        $this->session->set(self::SESSION_USUARIO, $usuario);
        return $this;
    }

    public function clearSession()
    {
        $this->session->remove(self::NAMESPACE);
    }

    public function prevRoute()
    {
        return $this->routes->getByStep($this->step - 1);
    }

    public function nextRoute()
    {
        return $this->routes->getByStep($this->step + 1);
    }

    public function setCurrentStepValid()
    {
        $this->routes->getByStep($this->step)->valid = true;
        if($this->sessionValidation) {
            $this->session->set(self::SESSION_STEP_VALID . '/' . $this->routes->getByStep($this->step)->key, true);
        }
    }
    public function setCurrentStepInvalid()
    {
        $this->routes->getByStep($this->step)->valid = false;
        if($this->sessionValidation) {
            $this->session->set(self::SESSION_STEP_VALID . '/' . $this->routes->getByStep($this->step)->key, false);
        }
    }

    public function validatePrevStepsValid(): ?HvWizardRoute
    {
        for ($i = 0; $i < $this->step; $i++) {
            if(!$this->routes->getByStep($i)->valid){
                $this->routes->getByStep($i);
            }
        }
        return null;
    }

    public function isStepValid($step)
    {
        return $this->routes->getByStep($step)->valid;
    }

    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @return HvWizardRoutes
     */
    public function getRoutes(): HvWizardRoutes
    {
        return $this->routes;
    }

    /**
     * @return bool|string
     */
    public function canNextRoute()
    {
        return $this->hvValidator->validateStep($this->routes->getByStep($this->step)->key, $this->getHv());
    }

    public function getValidator()
    {
        return $this->hvValidator;
    }
}