<?php


namespace App\Security\Voter;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Service\PortalClientes\PortalClientesService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AutoliquidacionVoter extends Voter
{
    private $security;
    /**
     * @var PortalClientesService
     */
    private $portalClientesService;

    public function __construct(Security $security, PortalClientesService $portalClientesService)
    {
        $this->security = $security;
        $this->portalClientesService = $portalClientesService;
    }

    protected function supports($attribute, $subject)
    {
        return $attribute === 'AUTOLIQUIDACION_MANAGE' &&
            ($subject instanceof Autoliquidacion || $subject instanceof AutoliquidacionEmpleado);
    }


    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case 'AUTOLIQUIDACION_MANAGE':
                if ($this->security->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
                    return true;
                }
                if($this->security->isGranted('ROLE_REPRESENTANTE_CLIENTE')) {
                    $convenio = $this->portalClientesService->getRepresentanteConvenio($user);
                    return $convenio == $subject->getConvenio();
                }
                if ($subject->getUsuario() == $user) {
                    return true;
                }
                return false;
        }

        return false;
    }
}