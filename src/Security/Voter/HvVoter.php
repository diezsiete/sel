<?php

namespace App\Security\Voter;

use App\Entity\HvEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class HvVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['HV_MANAGE']) && $subject instanceof HvEntity;
    }

    /**
     * @param string $attribute
     * @param HvEntity $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'HV_MANAGE':
                if ($this->security->isGranted('ROLE_ADMIN_VACANTE')) {
                    return true;
                }
                //esta editando hv en registro
                if(!$subject->getHv() || !is_object($user)) {
                    return true;
                }
                if ($subject->getHv()->getUsuario() == $user) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
