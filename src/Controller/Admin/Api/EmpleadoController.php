<?php


namespace App\Controller\Admin\Api;


use App\Controller\BaseController;
use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Repository\Main\ConvenioRepository;
use App\Service\Novasoft\Api\Importer\EmpleadoImporter;
use App\Service\Novasoft\Api\Client\NovasoftApiClient;
use Symfony\Component\Routing\Annotation\Route;

class EmpleadoController extends BaseController
{
    /**
     * @Route("/sel/admin/api/empleado/import/{identificacion}",
     *     methods="PUT",
     *     name="sel_admin_api_empleado_importar",
     *     options={"expose" = true},
     *     requirements={"identificacion"="\d+"}
     * )
     */
    public function import(string $identificacion, EmpleadoImporter $importer)
    {
        $empleado = $importer->importEmpleado($identificacion);
        return $this->json(['empleado' => $empleado ? $empleado->getUsuario() : null]);
    }
}