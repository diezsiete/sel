<?php


namespace App\Service\Scraper;


use App\Service\AutoliquidacionService;
use App\Service\Scraper\Exception\ScraperClientException;
use App\Service\Scraper\Exception\ScraperConflictException;
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

    /**
     * @param string $user
     * @param string $password
     * @param string $empleador
     * @param int $pageIndex
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function launch(string $user, string $password, string $empleador, $pageIndex = 1)
    {
        $this->scraperClient->get('/browser/launch/ael');
        $this->scraperClient->get('/browser/page/ael/page1');
        $this->scraperClient->post('/ael/login', ['user' => $user, 'password' => $password]);
        $this->scraperClient->get('/ael/empleador/' . $empleador);
        $this->scraperClient->get('/ael/certificados');
        return $this;
    }

    /**
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function close()
    {
        $this->scraperClient->get('/browser/close/ael');
        return $this;
    }

    /**
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function logout()
    {
        $this->scraperClient->get('/ael/logout');
        return $this;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function generatePdf(string $ident, DateTimeInterface $periodo)
    {
        $periodo = $periodo->format('Y-m');
        $this->scraperClient->get("/ael/download/$ident/$periodo");
        return $this;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return $this
     * @throws ScraperClientException
     */
    public function downloadPdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        $resource = $this->scraperClient->download("/ael/pdf/$ident/$periodoString");
        $this->autoliquidacionService->uploadPdfResource($periodo, $ident, $resource);
        return $this;
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function deletePdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        $this->scraperClient->delete("/ael/pdf/$ident/$periodoString");
        return $this;
    }

    /**
     * @return $this
     * @throws ScraperClientException
     * @throws ScraperConflictException
     * @throws ScraperException
     * @throws ScraperNotFoundException
     */
    public function clearIdent()
    {
        $this->scraperClient->get('/ael/clear-ident');
        return $this;
    }

    public function reload()
    {

    }

}