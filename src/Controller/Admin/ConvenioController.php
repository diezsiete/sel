<?php


namespace App\Controller\Admin;


use App\DataTable\Type\ConvenioDataTableType;
use App\DataTable\Type\RepresentanteDataTableType;
use App\Entity\Convenio;
use App\Entity\Representante;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ConvenioController extends AbstractController
{
    /**
     * @Route("/sel/admin/convenios", name="admin_convenio_list")
     */
    public function convenios(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(ConvenioDataTableType::class, [], ['searching' => true]);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/convenio/list.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}", name="admin_convenio")
     */
    public function convenio(Convenio $convenio)
    {
        return $this->render('admin/convenio/convenio.html.twig', ['convenio' => $convenio]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}/representantes", name="admin_convenio_representantes")
     */
    public function representantes(Convenio $convenio, DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(RepresentanteDataTableType::class, ['convenio' => $convenio], [
            'searching' => false
        ]);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('admin/convenio/representantes.html.twig', [
            'convenio' => $convenio,
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}/representante/{rid}", name="admin_convenio_representante_edit")
     * @ParamConverter("convenio", options={"mapping": {"codigo": "codigo"}})
     * @ParamConverter("representante", options={"mapping": {"rid": "id"}})
     */
    public function representanteEdit(Convenio $convenio, Representante $representante)
    {
        dump($convenio);
        dd($representante);
    }


    /**
     * @Route("/sel/admin/convenio/{codigo}/empleados", name="admin_convenio_empleados")
     */
    public function empleados(Convenio $convenio)
    {

    }
}