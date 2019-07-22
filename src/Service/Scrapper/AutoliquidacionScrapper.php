<?php


namespace App\Service\Scrapper;


use App\Service\AutoliquidacionService;
use DateTimeInterface;
use Exception;

class AutoliquidacionScrapper
{
    /**
     * @var ScrapperClient
     */
    private $scrapperClient;
    /**
     * @var AutoliquidacionService
     */
    private $autoliquidacionService;

    public function __construct(ScrapperClient $scrapperClient, AutoliquidacionService $autoliquidacionService)
    {
        $this->scrapperClient = $scrapperClient;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    public function launch(string $empleador, $pageIndex = 1)
    {
        $this->scrapperClient->get('/ael/login');
        $this->scrapperClient->get('/ael/empleador/' . $empleador);
        $this->scrapperClient->get('/ael/certificados');
        return true;
    }

    public function close()
    {
        $this->scrapperClient->get('/browser/close/ael');
        return true;
    }

    /**
     * @throws Exception
     */
    public function generatePdf(string $ident, DateTimeInterface $periodo): ScrapperResponse
    {
        $periodo = $periodo->format('Y-m');
        return $this->scrapperClient->get("/ael/download/$ident/$periodo");
    }

    public function downloadPdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        $resource = $this->scrapperClient->download("/pdf/download/$ident/$periodoString");
        $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
        return true;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return ScrapperResponse
     */
    public function deletePdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        return $this->scrapperClient->get("/pdf/delete/$ident/$periodoString");
    }

    /**
     * @return ScrapperResponse
     */
    public function clearIdent()
    {
        return $this->scrapperClient->get('/ael/clear-ident');
    }


}