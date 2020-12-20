<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpDocSignatureInspection
 */

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\DataTable\Type\Hv\AdminHvDataTableType;
use App\Entity\Hv\Hv;
use App\Repository\Hv\HvRepository;
use App\Repository\Hv\ReferenciaTipoRepository;
use App\Service\Halcon\Servicios\Pdf;
use Knp\Component\Pager\PaginatorInterface;
use League\Flysystem\FilesystemInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ASPIRANTES_MODULE", statusCode=404, message="Resource not found")
 */
class AdminHvController extends BaseController
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
    public function detalle(Hv $hv, ReferenciaTipoRepository $referenciaTipoRepo)
    {
        return $this->render('admin/hv/detalle/detalle.html.twig', [
            'hv' => $hv,
            'tiposReferencia' => $referenciaTipoRepo->getKeyPair()
        ]);
    }

    /**
     * @Route("/sel/admin/hv/{id}/pdf", name="admin_hv_pdf", requirements={"id"="\d+"})
     */
    public function detallePdf(Hv $hv, ReferenciaTipoRepository $referenciaTipoRepo, Pdf $halconPdf, $kernelProjectDir, FilesystemInterface $privateUploadFilesystem)
    {
        $response = $this->render('admin/hv/detalle/pdf.html.twig', [
            'hv' => $hv,
            'tiposReferencia' => $referenciaTipoRepo->getKeyPair()
        ]);
        // return $response;
        // $content = str_replace('localhost:8000', 'com.co', $response->getContent());
        $content = $response->getContent();
        $path = '/pdf/' . $hv->getIdentificacion() . '.pdf';
        $halconPdf->generarHtmlStreamBorrar($content, $kernelProjectDir . "/var/uploads$path");

        return $this->renderStream(function () use ($privateUploadFilesystem, $path) {
            return $privateUploadFilesystem->readStream($path);
        });
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
