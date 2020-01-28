<?php


namespace App\Controller\Admin\Api;


use App\Controller\BaseController;
use App\Repository\Main\UsuarioRepository;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    /**
     * @Route("sel/admin/api/search/usuario/{term}/{rol}",
     *     name="sel_admin_api_search_usuario",
     *     options={"expose" = true},
     *     defaults={"rol" = null}
     * )
     */
    public function searchUsuario($term, $rol, UsuarioRepository $usuarioRepository)
    {
        $rol = $rol ? "ROLE_" . strtoupper($rol) : null;
        return $this->json($usuarioRepository->search(urldecode($term), $rol));
    }
}