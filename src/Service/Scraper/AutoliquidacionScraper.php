<?php


namespace App\Service\Scraper;


use App\Service\Configuracion\Configuracion;
use App\Service\Scraper\Response\ScraperResponse;
use DateTimeInterface;

class AutoliquidacionScraper
{
    /**
     * @var ScraperClient
     */
    private $scraperClient;


    /**
     * @var string
     */
    private $empleador;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;


    public function __construct(Configuracion $configuracion, ScraperClient $scraperClient)
    {
        $this->scraperClient = $scraperClient;
        $this->user = $configuracion->getScraper()->getAel()->getUser();
        $this->password = $configuracion->getScraper()->getAel()->getPassword();
        $this->empleador = $configuracion->getScraper()->getAel()->getEmpleador();
    }

    /**
     * @param int $pageIndex
     * @return Response\ScraperResponse
     * @throws Exception\ScraperClientException
     * @throws Exception\ScraperConflictException
     * @throws Exception\ScraperException
     * @throws Exception\ScraperNotFoundException
     * @throws Exception\ScraperTimeoutException
     */
    public function launch($pageIndex = 1)
    {
        return $this->scraperClient->post("/ael/certificados/ael/$pageIndex/" . $this->empleador, [
            'user' => $this->user, 'password' => $this->password
        ]);
    }

    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @param int $pageIndex
     * @return ScraperResponse
     * @throws Exception\ScraperClientException
     * @throws Exception\ScraperConflictException
     * @throws Exception\ScraperException
     * @throws Exception\ScraperNotFoundException
     * @throws Exception\ScraperTimeoutException
     */
    public function generatePdf(string $ident, DateTimeInterface $periodo, $pageIndex = 1)
    {
        return $this->scraperClient->post("/ael/certificados/ael/$pageIndex/$this->empleador/$ident/".$periodo->format('Y-m'), [
            'user' => $this->user, 'password' => $this->password
        ]);
    }


    /**
     * @param string $ident
     * @param DateTimeInterface $periodo
     * @return bool|resource
     * @throws Exception\ScraperClientException
     * @throws Exception\ScraperConflictException
     * @throws Exception\ScraperException
     * @throws Exception\ScraperNotFoundException
     * @throws Exception\ScraperTimeoutException
     */
    public function downloadPdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        return $this->scraperClient->download("/ael/pdf/$ident/$periodoString");
    }

    public function deletePdf(string $ident, DateTimeInterface $periodo)
    {
        $periodoString = $periodo->format('Y-m');
        $this->scraperClient->delete("/ael/pdf/$ident/$periodoString");
        return $this;
    }

    public function logout($pageIndex = 1)
    {
        return $this->scraperClient->get("/ael/logout/ael/$pageIndex");
    }
}