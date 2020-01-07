<?php

namespace App\Security\Voter;

use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Hv\HvEntity;
use App\Entity\Main\ReporteNomina;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReporteVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['REPORTE_MANAGE']) && (
            $subject instanceof ReporteNomina || $subject instanceof AutoliquidacionEmpleado);
    }

    /**
     * @param string $attribute
     * @param \App\Entity\Main\ReporteNomina $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        switch ($attribute) {
            case 'REPORTE_MANAGE':
                if ($this->security->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
                    return true;
                }
                if ($subject->getUsuario() == $user) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
