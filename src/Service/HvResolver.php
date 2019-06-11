<?php


namespace App\Service;


use App\Entity\Hv;
use App\Entity\Usuario;
use App\Repository\HvRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class HvResolver
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var HvRepository
     */
    private $hvRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(Security $security, HvRepository $hvRepository, SessionInterface $session)
    {
        $this->security = $security;
        $this->hvRepository = $hvRepository;
        $this->session = $session;
    }

    public function getHv(): ?Hv
    {
        /** @var Usuario $usuario */
        if($usuario = $this->security->getUser()) {
            return $this->hvRepository->findByUsuario($usuario);
        }
        return $this->getSessionHv();
    }

    public function getSessionHv()
    {
        // return $this->hvRepository->findByUsuario(927);
        if($hvId = $this->session->get(RegistroWizard::SESSION_ID)){
            return $this->hvRepository->find($hvId);
        }
        return null;
    }
}