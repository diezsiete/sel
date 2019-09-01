<?php

namespace App\Controller;

use App\Entity\Hv;
use App\Repository\HvRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ASPIRANTES_MODULE", statusCode=404, message="Resource not found")
 */
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

    /**
     * @Route("/sel/admin/hv/{id}", name="admin_hv_detalle")
     */
    public function detalle(Hv $hv)
    {
        return $this->render('hv_admin/detalle/detalle.html.twig', [
            'hv' => $hv
        ]);
    }
}
