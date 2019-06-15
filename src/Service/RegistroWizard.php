<?php


namespace App\Service;


use App\Constant\HvConstant;
use App\Entity\Hv;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistroWizard
{
    const SESSION_ID = 'registro/hv_id';
    const SESSION_USUARIO = 'registro/usuario';
    const SESSION_STEP = 'registro/step';
    const SESSION_STEP_VALID = 'registro/step_valid';
    
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
    

    private $routes = [
        'registro_datos_basicos',
        'registro_estudios',
        'registro_experiencia',
        'registro_referencias',
        'registro_familiares',
        'registro_cuenta'
    ];

    public function __construct(HvResolver $hvResolver, SessionInterface $session, RequestStack $requestStack)
    {

        $this->session = $session;
        $this->hvResolver = $hvResolver;

        $request = $requestStack->getCurrentRequest();
        if($request->isXmlHttpRequest()) {
            $this->step = $this->session->get(self::SESSION_STEP) ?? 0;
        } else {
            $searchStep = array_search($requestStack->getCurrentRequest()->get('_route'), $this->routes);
            $this->step = $searchStep === false ? 0 : $searchStep;
            $this->session->set(self::SESSION_STEP, $this->step);
        }

        if(!$this->session->get(self::SESSION_STEP_VALID)) {
            $sessionStepValid = [];
            foreach($this->routes as $route) {
                $sessionStepValid[$route] = false;
            }
            $this->session->set(self::SESSION_STEP_VALID, $sessionStepValid);
        }
    }

    public function getHv()
    {
        return $this->hvResolver->getSessionHv() ?? new Hv();
    }

    public function getUsuario()
    {
        if(!$usuario = $this->session->get(self::SESSION_USUARIO)) {
            $usuario = new Usuario();
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
        $this->session->remove('registro');
    }

    public function prevRoute()
    {
        return $this->routes[$this->step - 1] ?? null;
    }

    public function nextRoute()
    {
        return $this->routes[$this->step + 1] ?? null;
    }

    public function setCurrentStepValid()
    {
        $this->session->set(self::SESSION_STEP_VALID . '/' . $this->routes[$this->step], true);
    }
    public function setCurrentStepInvalid()
    {
        $this->session->set(self::SESSION_STEP_VALID . '/' . $this->routes[$this->step], false);
    }

    public function validatePrevStepsValid(): ?string
    {
        for ($i = 0; $i < $this->step; $i++) {
            if(!$this->session->get(self::SESSION_STEP_VALID . '/' . $this->routes[$i])) {
                return $this->routes[$i];
            }
        }
        return null;
    }

    /**
     * @return bool|string
     */
    public function canNextRoute()
    {
        $errorMessage = null;
        switch($this->step) {
            case 1:
                $errorMessage = $this->validarEstudios();
                break;
            case 2:
                $errorMessage = $this->validarExperiencia();
                break;
            case 3:
                $errorMessage = $this->validarReferencias();
                break;
            case 4:
                $errorMessage = $this->validarFamiliares();
                break;
        }
        return $errorMessage === true ? true : $errorMessage;
    }

    protected function validarEstudios()
    {
        return $this->getHv()->getEstudios()->count() === 0 ? "Se necesita minimo un registro de estudios" : true;
    }

    protected function validarExperiencia()
    {
        return $this->getHv()->getExperiencia()->count() === 0 ? "Se necesita minimo un registro de experiencia" : true;
    }

    protected function validarReferencias()
    {
        $referenciasRequired = HvConstant::REFERENCIA_TIPO;
        foreach($this->getHv()->getReferencias() as $referencia) {
            unset($referenciasRequired[$referencia->getTipo()]);
        }
        if($referenciasRequired) {
            $lastReferencia = array_pop($referenciasRequired);
            return "Falta completar referencias de tipo : " . implode(", ", $referenciasRequired)
                . (count($referenciasRequired) ? " y " : " ") . $lastReferencia;
        }
        return true;
    }

    private function validarFamiliares()
    {
        return true;
    }
}