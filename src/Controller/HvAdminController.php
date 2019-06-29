<?php

namespace App\Controller;

use App\DataTable\Type\Hv\HvDataTableType;
use App\Repository\HvRepository;
use Knp\Component\Pager\PaginatorInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HvAdminController extends AbstractController
{
    /**
     * @Route("/sel/admin/hv/listado", name="admin_hv_listado")
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
