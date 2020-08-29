<?php


namespace App\Service\Hv;


use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Entity\Main\Usuario;
use App\Repository\Hv\HvRepository;
use App\Service\Hv\HvWizard\HvWizard;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class HvResolver
 * @package App\Service\Hv
 * @deprecated
 */
class HvResolver implements HvEntity
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var \App\Repository\Hv\HvRepository
     */
    private $hvRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var \App\Entity\Main\Usuario
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
        if($hvId = $this->session->get(HvWizard::SESSION_ID)){
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

    public function getNapiId(): string
    {
        return '';
    }
}