<?php


namespace App\Controller\Admin\Api;


use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchivoController extends BaseController
{
    /**
     * @Route("/sel/admin/api/archivo", methods="POST", name="sel_admin_api_archivo", options={"expose" = true})
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        return $this->json(['ok' => 1]);
    }
}