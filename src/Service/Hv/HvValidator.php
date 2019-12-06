<?php


namespace App\Service\Hv;


use App\Entity\Hv;
use App\Service\Configuracion\Configuracion;
use App\Service\Hv\HvWizard\HvWizardRoute;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HvValidator
{
    const SESSION_NAMESPACE = 'hv/validator';

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var HvWizard\HvWizardRoute[]|array
     */
    private $hvWizardRoutes;
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(ValidatorInterface $validator, Configuracion $configuracion)
    {
        $this->validator = $validator;
        $this->hvWizardRoutes = $configuracion->getHvWizardRoutes();
        $this->configuracion = $configuracion;
    }

    /**
     * @param Hv $hv
     * @return HvWizardRoute[]
     */
    public function validateWizardRoutes(?Hv $hv)
    {
        foreach($this->hvWizardRoutes as $hvWizardRoute) {
            $errorMessage = $this->validateStep($hvWizardRoute->key, $hv, false);
            if ($errorMessage !== false) {
                $hvWizardRoute->valid = $errorMessage === true;
            }
        }
        return $this->hvWizardRoutes;
    }

    /**
     * @param bool $first
     * @return HvWizardRoute[]|HvWizardRoute|null
     */
    public function getRoutesInvalidas($first = false)
    {
        $routesInvalidas = [];
        foreach($this->hvWizardRoutes as $hvWizardRoute) {
            if(!$hvWizardRoute->valid) {
                $routesInvalidas[] = $hvWizardRoute;
            }
        }
        if($first) {
            return $routesInvalidas ? $routesInvalidas[0] : null;
        }
        return $routesInvalidas;
    }

    /**
     * @return bool|string
     */
    public function validateStep($stepKey, ?Hv $hv, $noMethodReturn = true)
    {
        $validarMethod = "validate" . ucfirst($stepKey);
        if(method_exists($this, $validarMethod)) {
            return $this->$validarMethod($hv);
        } else {
            return $noMethodReturn;
        }
    }

    public function validateBasicos(?Hv $hv)
    {
        $errorMessage = false;
        if(!$hv || !$hv->getUsuario()) {
            $errorMessage = "Datos básicos invalidos";
        }
        if(!$errorMessage) {
            if (count($this->validator->validate($hv))) {
                $errorMessage = "Datos básicos invalidos";
            }
        }
        if(!$errorMessage) {
            if(count($this->validator->validate($hv->getUsuario()))) {
                $errorMessage = "Datos básicos invalidos";
            }
        }
        return $errorMessage === false ? true : $errorMessage;
    }

    public function validateEstudios(?Hv $hv)
    {
        return !$hv || $hv->getEstudios()->count() === 0 ? "Se necesita minimo un registro de estudios" : true;
    }

    public function validateExperiencia(?Hv $hv)
    {
        return !$hv || $hv->getExperiencia()->count() === 0 ? "Se necesita minimo un registro de experiencia" : true;
    }

    public function validateReferencias(?Hv $hv)
    {
        $referenciasRequired = $this->configuracion->getHvReferenciaTipo();
        if($hv) {
            foreach ($hv->getReferencias() as $referencia) {
                unset($referenciasRequired[$referencia->getTipo()]);
            }
        }
        if($referenciasRequired) {
            $lastReferencia = array_pop($referenciasRequired);
            return "Falta completar referencias de tipo : " . implode(", ", $referenciasRequired)
                . (count($referenciasRequired) ? " y " : " ") . $lastReferencia;
        }
        return true;
    }

    public function validateFamiliares(?Hv $hv)
    {
        return $hv ? true : false;
    }

    public function setRoutes(array $routes)
    {
        $this->hvWizardRoutes = $routes;
    }
}