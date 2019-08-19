<?php


namespace App\Controller\Admin;


use App\DataTable\Type\ConvenioDataTableType;
use App\Entity\Convenio;
use Omines\DataTablesBundle\DataTableFactory;
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
}