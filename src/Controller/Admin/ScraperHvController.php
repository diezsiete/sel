<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\DataTable\Type\Scraper\MessageHvDataTableType;
use App\Entity\Hv;
use App\Entity\Scraper\MessageHv;
use App\Repository\Scraper\MessageHvRepository;
use App\Service\Scraper\ScraperMessenger;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ScraperHvController extends BaseController
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


    /**
     * @Route("/sel/admin/scraper/hv/message-queue", name="admin_scraper_message_queue", options={"expose" = true})
     */
    public function getMessageQueue(Request $request, MessageHvRepository $messageHvRepository)
    {
        $ids = $request->request->get('ids');
        $response = $messageHvRepository->findMessageQueue($ids);

        return $this->json($response);
    }

    /**
     * @Route("/sel/admin/scraper/hv/retry-failed-message/{hvId}", name="admin_scraper_retry_failed_message", options={"expose" = true})
     */
    public function retryFailedMessage(MessageHvRepository $messageHvRepository, ScraperMessenger $scraperMessenger, $hvId)
    {
        $failedMessages = $messageHvRepository->findFailedByHvId($hvId);
        foreach($failedMessages as $failedMessage) {
            $this->em()->remove($failedMessage);
        }
        $this->em()->flush();

        $scraperMessenger->insertToNovasoft($this->getDoctrine()->getRepository(Hv::class)->find($hvId));

        return $this->json([$hvId => 'default']);
    }


}