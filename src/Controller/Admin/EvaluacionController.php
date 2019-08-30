<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\DataTable\Type\EvaluacionProgresoDataTableType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EvaluacionController extends BaseController
{
    /**
     * @Route("/sel/admin/evaluacion/resultados", name="admin_evaluacion_resultados")
     */
    public function resultados(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(EvaluacionProgresoDataTableType::class, [], ['searching' => true]);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/evaluacion/resultados.html.twig', ['datatable' => $table]);
    }
}