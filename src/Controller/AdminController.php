<?php

namespace App\Controller;

use App\Entity\Convenio;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/convenios", name="admin_convenios", defaults={"header": "Convenios"})
     */
    public function convenios(DataTableFactory $dataTableFactory, Request $request)
    {
        $dom = "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>"
             . "<'row'<'col-sm-12'tr>>"
             . "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
        $table = $dataTableFactory->create(['searching' => true, 'dom' => $dom])
            ->add('codigo', TextColumn::class, ['label' => 'Codigo'])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('direccion', TextColumn::class, ['label' => 'Direccion'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Convenio::class,
            ])
        ;

        $table->addOrderBy($table->getColumn(0),DataTable::SORT_DESCENDING);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/convenios.html.twig', ['datatable' => $table]);
    }
}
