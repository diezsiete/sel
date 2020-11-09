<?php

namespace App\Service\Ael;

use App\Helper\Halcon\Terminal\Proceso;
use App\Service\Configuracion\Configuracion;
use App\Service\Halcon\Servicios\Terminal;
use App\Service\HttpClient;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
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
    /**
     * @var Terminal
     */
    private $terminal;

    public function __construct(HttpClientInterface $httpClient, Configuracion $configuracion, Terminal $terminal)
    {
        parent::__construct($httpClient);
        $this->configuracion = $configuracion;
        $this->user = $configuracion->ael()->getUser();
        $this->password = $configuracion->ael()->getPassword();
        $this->empleador = $configuracion->ael()->getEmpleador();
        $this->terminal = $terminal;
    }

    public function start()
    {
        $proceso = new Proceso('pm2',
            ['start', 'ecosystem.config.js', '--env=production'], '/home/ubuntu/ael', ['HOME' => '/home/ubuntu']
        );
        $response = $this->terminal->ejecutar($proceso);
        $exito = strstr($response, '[PM2] App [ael] launched') !== false
            || strstr($response,'[PM2] [ael](0)') !== false;

        if(!$exito) {
            throw new \Exception('PM2 no se inicio correctamente : ' . $response);
        }
        return true;
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

    public function delete()
    {
        $proceso = new Proceso('pm2', ['delete', 'ael'], '/home/ubuntu/ael', ['HOME' => '/home/ubuntu']);
        try {
            $response = $this->terminal->ejecutar($proceso);
            $exito = strstr($response,'[PM2] [ael](0)') !== false;
            if(!$exito) {
                throw new \Exception('PM2 no se inicio correctamente : ' . $response);
            }
        } catch (ServerExceptionInterface $e) {
            if(strstr($e->getMessage(), '[PM2][ERROR] Process or Namespace ael not found') === false) {
                throw $e;
            }
            $exito = true;
        }
        return $exito;
    }

    private function url($url, $params = [])
    {
        return $this->configuracion->ael()->getUrl() . $this->addParametersToUrl($url, $params);
    }
}