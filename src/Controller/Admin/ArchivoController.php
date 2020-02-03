<?php


namespace App\Controller\Admin;



use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class ArchivoController extends BaseController
{
    /**
     * @Route("/sel/admin/archivo", name="sel_admin_archivo")
     */
    public function cargar()
    {
        return $this->render('admin/archivo/cargar.html.twig');
    }
}