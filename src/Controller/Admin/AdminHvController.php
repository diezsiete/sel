<?php

namespace App\Controller\Admin;

use App\DataTable\Type\Hv\AdminHvDataTableType;
use App\Entity\Hv;
use App\Repository\HvRepository;
use Knp\Bundle\TimeBundle\Twig\Extension\TimeExtension;
use Knp\Component\Pager\PaginatorInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ASPIRANTES_MODULE", statusCode=404, message="Resource not found")
 */
class AdminHvController extends AbstractController
{
    /**
     * @Route("/sel/admin/hv/listado", name="admin_hv_listado")
     */
    public function listadoTemp(Request $request, DataTableFactory $dataTableFactory)
    {

        $table = $dataTableFactory->createFromType(AdminHvDataTableType::class, [], ['searching' => true]);
        $table->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/hv/list.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/hv/{id}", name="admin_hv_detalle", requirements={"id"="\d+"})
     */
    public function detalle(Hv $hv)
    {
        return $this->render('admin/hv/detalle/detalle.html.twig', [
            'hv' => $hv
        ]);
    }


    /**
     * @Route("/sel/admin/hv/listado-old", name="admin_hv_listado_old")
     */
    public function listado(HvRepository $hvRepository, PaginatorInterface $paginator, Request $request)
    {
        $search = $request->get('search', null);
        $qb = $hvRepository->searchQueryBuilder($search);

        $pagination = $paginator->paginate($qb, $request->get('page', 1), 25);

        return $this->render('hv_admin/listado.html.twig', [
            'pagination' => $pagination,
            'search' => $search
        ]);
    }
}
