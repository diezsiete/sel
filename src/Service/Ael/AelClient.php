<?php

namespace App\Service\Ael;

use App\Service\Configuracion\Configuracion;
use App\Service\HttpClient;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AelClient extends HttpClient
{
    /**
     * @var Configuracion
     */
    private $configuracion;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $empleador;

    public function __construct(HttpClientInterface $httpClient, Configuracion $configuracion)
    {
        parent::__construct($httpClient);
        $this->configuracion = $configuracion;
        $this->user = $configuracion->ael()->getUser();
        $this->password = $configuracion->ael()->getPassword();
        $this->empleador = $configuracion->ael()->getEmpleador();
    }


    public function login()
    {
        $url = $this->url('/ael/login');
        $response =  $this->postJson($url, ['user' => $this->user, 'password' => $this->password]);
        return $response->toArray();
    }


    public function certificadoDownload(string $ident, DateTimeInterface $periodo, $pageIndex = 1)
    {
        $url = $this->url('/ael/:empleador/certificados/:ident/:periodo', [
            'empleador' => $this->empleador,
            'ident' => $ident,
            'periodo' => $periodo->format('Y-m')
        ]);
        $response =  $this->postJson($url, ['user' => $this->user, 'password' => $this->password]);
        return $response->toArray();
    }

    public function pdfDownload(string $ident, DateTimeInterface $periodo)
    {
        return $this->download($this->url('/ael/pdf/:ident/:periodo', [
            'ident' => $ident,
            'periodo' => $periodo->format('Y-m')
        ]));
    }

    public function pdfDelete(string $ident, DateTimeInterface $periodo)
    {
        $response = $this->deleteJson($this->url('/ael/pdf/:ident/:periodo', [
            'ident' => $ident,
            'periodo' => $periodo->format('Y-m')
        ]));
        return $response->toArray();
    }

    private function url($url, $params = [])
    {
        if($params && preg_match_all('/:(\w+)/', $url, $matches)) {
            for($i = 0, $iMax = count($matches[0]); $i < $iMax; $i++) {
                $url = str_replace($matches[0][$i], $params[$matches[1][$i]], $url);
            }
        }
        return $this->configuracion->ael()->getUrl() . $url;
    }
}