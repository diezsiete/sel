<?php

namespace App\Controller;

use App\DataTable\Type\AutoliquidacionDataTableType;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Convenio;
use App\Service\Autoliquidacion\Export;
use App\Service\Autoliquidacion\FileManager;
use DateTime;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/sel/admin/autoliquidaciones/export/{id}/{type}", name="admin_autoliquidacion_export", defaults={"type"="zip"})
     */
    public function export(Autoliquidacion $autoliquidacion, $type, Export $export)
    {
        return $this->renderStream(function () use($autoliquidacion, $export) {
            return $export
                ->generate($autoliquidacion, $this->getUser())
                ->stream($autoliquidacion, $this->getUser());
        }, 'application/pdf', $autoliquidacion->getConvenio()->getCodigo() . ".$type");
    }
}
