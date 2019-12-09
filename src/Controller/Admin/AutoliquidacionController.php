<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\DataTable\Type\AutoliquidacionDataTableType;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Convenio;
use App\Service\Autoliquidacion\Export;
use DateTime;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AutoliquidacionController extends BaseController
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

    /**
     * @Route("/sel/admin/autoliquidaciones/export/{id}/{type}", name="admin_autoliquidacion_export")
     */
    public function export(Autoliquidacion $autoliquidacion, $type, Export $export)
    {
        if($type === 'pdf') {
            return $this->renderStream(function () use ($autoliquidacion, $export) {
                $export->generate($autoliquidacion, $this->getUser());
                return $export->stream($autoliquidacion, $this->getUser());
            }, 'application/' . $type, $autoliquidacion->getConvenio()->getCodigo() . ".$type");
        } else {
            return $this->renderZip($export->generate($autoliquidacion), $autoliquidacion->getConvenio()->getCodigo() . ".zip");
        }
    }
}