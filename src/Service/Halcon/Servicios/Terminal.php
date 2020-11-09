<?php


namespace App\Service\Halcon\Servicios;


use App\Helper\Halcon\Terminal\Proceso;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Terminal extends HalconServicio
{
    /**
     * @param Proceso|string $proceso
     * @param array $opciones
     * @param null $cwd
     * @param array $env
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function ejecutar($proceso, $opciones = [], $cwd = null, $env = [])
    {
        if(is_string($proceso)) {
            $proceso = new Proceso($proceso, $opciones, $cwd, $env);
        }

        $request = $this->client->request($this->configuracion->servicios->terminal->ejecutar);
        $request->body['comando'] = $proceso->getComando();

        if($opciones = $proceso->getOpciones()) {
            $request->body['opcion'] = $opciones;
        }
        if($cwd = $proceso->getCwd()) {
            $request->body['cwd'] = $cwd;
        }
        if($env = $proceso->getEnv()) {
            $request->body['env'] = $env;
        }

        $respuesta = $request->post()->toArray();
        if(isset($respuesta['salida'])) {
            return $respuesta['salida'];
        }
        throw new Exception('Respuesta no reconocida');
    }
}