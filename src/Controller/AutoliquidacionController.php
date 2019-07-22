<?php

namespace App\Controller;

use App\DataTable\Type\AutoliquidacionDataTableType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AutoliquidacionController extends AbstractController
{
    /**
     * @Route("/sel/admin/autoliquidaciones", name="admin_autoliquidaciones")
     */
    public function index(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory
            ->createFromType(AutoliquidacionDataTableType::class, [], ['searching' => true])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('autoliquidaciones/index.html.twig', ['datatable' => $table]);
    }
}
