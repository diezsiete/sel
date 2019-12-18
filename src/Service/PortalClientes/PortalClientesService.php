<?php


namespace App\Service\PortalClientes;


use App\Entity\Convenio;
use App\Entity\Usuario;
use App\Repository\RepresentanteRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class PortalClientesService
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var RepresentanteRepository
     */
    private $representanteRepository;

    public function __construct(Security $security, RepresentanteRepository $representanteRepository)
    {
        $this->security = $security;
        $this->representanteRepository = $representanteRepository;
    }

    /**
     * @param Usuario|UserInterface $usuario
     * @return Convenio|null
     * @throws NonUniqueResultException
     */
    public function getRepresentanteConvenio($usuario = null)
    {
        $convenio = null;
        $usuario = $usuario ? $usuario : $this->security->getUser();
        if(!$this->security->isGranted('ROLE_ADMIN_AUTOLIQUIDACIONES', $usuario)) {
            if($representante = $this->representanteRepository->findByUsuario($usuario)) {
                $convenio = $representante->getConvenio();
            } else {
                throw new Exception("Usuario {$usuario->getId()} acceso a admin autoliquidacion, no es admin y no es representante");
            }
        }
        return $convenio;
    }
}