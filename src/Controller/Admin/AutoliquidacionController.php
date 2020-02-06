<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\DataTable\SelDataTableFactory;
use App\DataTable\Type\AutoliquidacionDataTableType;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\Clientes\ConvenioDataTableType;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Main\Convenio;
use App\Repository\Main\RepresentanteRepository;
use App\Service\Autoliquidacion\DatabaseActions;
use App\Service\Autoliquidacion\Export;
use App\Service\PortalClientes\PortalClientesService;
use DateTime;
use Exception;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AutoliquidacionController extends BaseController
{
    /**
     * @Route("/sel/admin/autoliquidacion", name="admin_autoliquidacion_list")
     */
    public function list(DataTableFactory $dataTableFactory, Request $request, PortalClientesService $portalClientesService)
    {
        $datableOptions = [];
        if($convenio = $portalClientesService->getRepresentanteConvenio()) {
            $datableOptions['convenio'] = $convenio;
        }

        $table = $dataTableFactory
            ->createFromType(AutoliquidacionDataTableType::class, $datableOptions, ['searching' => true])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('admin/autoliquidacion/list.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/autoliquidacion/generar/{periodo}", name="admin_autoliquidacion_generar", defaults={"periodo"=""})
     * @IsGranted("ROLE_ADMIN_AUTOLIQUIDACIONES", statusCode=404, message="Resource not found")
     */
    public function generar(DatabaseActions $databaseActions, Request $request, $periodo, SelDataTableFactory $dataTableFactory)
    {
        $formBuilder = $this->createFormBuilder();
        $form = $formBuilder
            ->add('periodo', ChoiceType::class, [
                'choices' => $databaseActions->getPeriodos(),
                'data' => $periodo
            ])
            ->add('submit', SubmitType::class, [
                'label' => $periodo ? 'Generar' : 'Seleccionar'
            ]);

        $datatable = null;
        if(!$periodo) {
            $formBuilder->setMethod('GET');
        } else {
            $datatable = ($dataTableFactory->createFromType(ConvenioDataTableType::class, ['form' => $form]))
                ->handleRequest($request);

            if ($datatable->isCallback()) {
                return $datatable->getResponse();
            }
        }

        $form = $form->getForm()->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$periodo) {
                return $this->redirectToRoute('admin_autoliquidacion_generar', ['periodo' => $form['periodo']->getData()]);
            } else {
                $x = $form['datatable']->getData();
                //dump($x);
            }
        }

        return $this->render('admin/autoliquidacion/generar.html.twig', [
            'formGenerar' => $form->createView(),
            'datatable' => $datatable
        ]);
    }

    /**
     * @Route("/sel/admin/autoliquidacion/{codigo}/{periodo}", name="admin_autoliquidacion_detalle")
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
     * @Route("/sel/admin/autoliquidacion/export/{id}/{type}", name="admin_autoliquidacion_export")
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
