<?php

namespace App\Controller;

use App\DataTable\Type\Hv\HvDataTableType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HvAdminController extends AbstractController
{
    /**
     * @Route("/admin/hv/listado", name="admin_hv_listado")
     */
    public function listado(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(HvDataTableType::class)
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('hv_admin/listado.html.twig', ['datatable' => $table]);
    }
}
