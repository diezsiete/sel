<?php

namespace App\Controller;

use App\DataTable\Type\AutoliquidacionDataTableType;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\Entity\Convenio;
use DateTime;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AutoliquidacionController extends AbstractController
{
    /**
     * @Route("/sel/admin/autoliquidaciones", name="admin_autoliquidaciones")
     */
    public function list(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory
            ->createFromType(AutoliquidacionDataTableType::class, [], ['searching' => true])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('autoliquidaciones/index.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/autoliquidaciones/{codigo}/{periodo}", name="admin_autoliquidacion_detalle")
     */
    public function detalle(DataTableFactory $dataTableFactory, Request $request, Convenio $convenio, DateTime $periodo)
    {
        $table = $dataTableFactory
            ->createFromType(AutoliquidacionEmpleadoDataTableType::class, [
                'convenio' => $convenio,
                'periodo' => DateTime::createFromFormat('Y-m-d', $periodo->format('Y-m'). '-01')
            ], ['searching' => true])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('autoliquidaciones/detalle.html.twig', [
            'datatable' => $table,
            'convenio' => $convenio,
            'periodo' => $periodo
        ]);
    }

}
