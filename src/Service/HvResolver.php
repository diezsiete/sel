<?php


namespace App\Service;


use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\Usuario;
use App\Repository\HvRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class HvResolver implements HvEntity
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

    /**
     * @var Usuario
     */
    private $usuario = null;

    public function __construct(Security $security, HvRepository $hvRepository, SessionInterface $session, TokenStorageInterface $t)
    {
        $this->security = $security;
        $this->hvRepository = $hvRepository;
        $this->session = $session;
    }

    public function getHv(): ?Hv
    {
        if($usuario = $this->getUsuario()) {
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


    public function getId(): ?int
    {
        $hv = $this->getHv();
        return $hv ? $hv->getId() : null;
    }

    public function getUsuario()
    {
        if(!$this->usuario) {
            $this->usuario = $this->security->getUser();
        }
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
}