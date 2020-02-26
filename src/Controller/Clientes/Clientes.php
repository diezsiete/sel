<?php


namespace App\Controller\Clientes;


use App\Controller\BaseController;
use App\Repository\Main\ConvenioRepository;
use App\Service\PortalClientes\PortalClientesService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Clientes
 * @package App\Controller\Clientes
 * TODO seguridad solo a roles pertinentes
 */
class Clientes extends BaseController
{
    /**
     * @Route("/sel/clientes/{etc}", name="clientes", defaults={"etc":null}, requirements={"etc":".*"})
     */
    public function clientes(PortalClientesService $portalClientesService, ConvenioRepository $convenioRepo)
    {
        $user = $this->getUser();
        $convenio = $portalClientesService->getConvenio($user);
        $isAdmin = $user->esRol(['/ADMIN/', '/SERVICIO/']);
        $convenio = $convenioRepo->find('ALMLAN');
        return $this->render('/clientes/clientes.html.twig', [
            'convenio' => $convenio,
            'isAdmin'  => $isAdmin
        ]);
    }
}