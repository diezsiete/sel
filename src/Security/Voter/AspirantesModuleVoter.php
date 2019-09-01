<?php


namespace App\Security\Voter;


use App\Service\Configuracion\Configuracion;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AspirantesModuleVoter extends Voter
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ASPIRANTES_MODULE']);
    }


    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return !!$this->configuracion->getHvWizardRoutes();
    }
}