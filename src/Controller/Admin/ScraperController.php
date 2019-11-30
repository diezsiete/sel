<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\DataTable\Type\Scraper\MessageHvDataTableType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ScraperController extends BaseController
{
    /**
     * @Route("/sel/admin/scraper/hv/list/{queue}", name="admin_scraper_hv_list", defaults={"queue": "error"})
     */
    public function hvList(DataTableFactory $dataTableFactory, Request $request, $queue)
    {
        $table = $dataTableFactory->createFromType(MessageHvDataTableType::class, ['queue' => $queue], ['searching' => true]);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/scraper/hv-list.html.twig', [
            'datatable' => $table,
            'queue' => $queue
        ]);
    }
}