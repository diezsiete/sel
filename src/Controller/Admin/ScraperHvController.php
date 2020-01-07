<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\DataTable\Type\Scraper\MessageHvDataTableType;
use App\DataTable\Type\Scraper\SolicitudHvDataTableType;
use App\Entity\Main\Convenio;
use App\Entity\Hv\Hv;
use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use App\Entity\Scraper\Solicitud;
use App\Repository\Scraper\MessageHvRepository;
use App\Repository\Scraper\SolicitudRepository;
use App\Service\Scraper\ScraperMessenger;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ScraperHvController extends BaseController
{
    /**
     * @Route("/sel/admin/scraper/solicitud/list", name="admin_scraper_solicitud_list")
     */
    public function solicitudList(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(SolicitudHvDataTableType::class, ['searching' => true]);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/scraper/solicitud-list.html.twig', [
            'datatable' => $table
        ]);
    }


    /**
     * Llamada via ajax para obtener para hvs en que cola se encuentra
     * @Route("/sel/admin/scraper/hv/message-queue", name="admin_scraper_message_queue", options={"expose" = true})
     */
    public function getMessageQueue(Request $request, SolicitudRepository $solicitudRepository)
    {
        $ids = $request->request->get('ids');
        $solicitudes = $solicitudRepository->findLastSolicitud($ids);
        $response = [];
        foreach($solicitudes as $solicitud) {
            $response[$solicitud->getHv()->getId()] = $solicitud->getEstado();
            $ids = array_diff($ids, [$solicitud->getHv()->getId()]);
        }
        //los ids que no se encontraron solicitudes asociadas
        foreach($ids as $id) {
            $response[$id] = 0;
        }

        return $this->json($response);
    }

    /**
     * LLamada via ajax para reejecutar un mensaje hv. Borra todos los fallados para esa hv y vuelve a crear uno nuevo
     * @Route("/sel/admin/scraper/hv/retry-failed-message/{hvId}", name="admin_scraper_retry_failed_message", options={"expose" = true})
     */
    public function retryFailedMessage(SolicitudRepository $solicitudRepository, ScraperMessenger $scraperMessenger, $hvId)
    {
        $failedMessages = $solicitudRepository->findFailedByHvId($hvId);
        foreach($failedMessages as $failedMessage) {
            $this->em()->remove($failedMessage);
        }
        $this->em()->flush();

        $scraperMessenger->insertToNovasoft($this->getDoctrine()->getRepository(Hv::class)->find($hvId));

        return $this->json([$hvId => 4]);
    }


    /**
     * @Route("/sel/admin/scraper/hv/log/{id}", name="admin_scraper_solicitud_log", options={"expose" = true})
     */
    public function getMessageLog(Solicitud $solicitud)
    {
        return $this->json(['log' => $solicitud->getLog()]);
    }
}