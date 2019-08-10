<?php


namespace App\Service\Scraper;


use App\Service\AutoliquidacionService;
use App\Service\Scraper\Exception\ScraperException;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use App\Service\Scraper\Response\ScraperResponse;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AutoliquidacionScraper
{
    /**
     * @var ScraperClient
     */
    private $scraperClient;
    /**
     * @var AutoliquidacionService
     */
    private $autoliquidacionService;

    public function __construct(ScraperClient $scraperClient, AutoliquidacionService $autoliquidacionService)
    {
        $this->scraperClient = $scraperClient;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    public function launch(string $empleador, $pageIndex = 1)
    {
        $this->scraperClient->get('/ael/login');
        $this->scraperClient->get('/ael/empleador/' . $empleador);
        $this->scraperClient->get('/ael/certificados');
        return true;
    }

    public function close()
    {
        $this->scraperClient->get('/browser/close/ael');
        return $this;
    }

    public function logout()
    {
        $this->scraperClient->get('/ael/logout');
        return $this;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return ScraperResponse
     * @throws ScraperNotFoundException
     * @throws ScraperException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function generatePdf(string $ident, DateTimeInterface $periodo): ScraperResponse
    {
        $periodo = $periodo->format('Y-m');
        return $this->scraperClient->get("/ael/download/$ident/$periodo");
    }

    public function downloadPdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        $resource = $this->scraperClient->download("/pdf/download/$ident/$periodoString");
        $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
        return true;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return ScraperResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function deletePdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        return $this->scraperClient->get("/pdf/delete/$ident/$periodoString");
    }

    /**
     * @return ScraperResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ScraperException
     * @throws ScraperNotFoundException
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function clearIdent()
    {
        return $this->scraperClient->get('/ael/clear-ident');
    }

    public function reload()
    {

    }

}