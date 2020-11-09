<?php

namespace App\Service\Halcon\Servicios;

use App\Helper\Halcon\Mensaje;
use App\Service\Configuracion\Halcon\Halcon;
use App\Service\HttpClient\HttpClient;

class Correo extends HalconServicio
{
    public function mensaje()
    {
        return new Mensaje();
    }

    public function enviar(Mensaje $mensaje)
    {
        $request = $this->client->request($this->configuracion->servicios->correo->enviar);
        $request->body = [
            'to' => $mensaje->to,
            'cc' => $mensaje->cc,
            'bcc' => $mensaje->bcc,
            'subject' => $mensaje->subject,
            'html' => $mensaje->html
        ];

        $request->body->file['attachments'] = $mensaje->attachments;


        return $request->post()->toArray();
    }

    public function adjuntoLimite()
    {
        $response = $this->client->get($this->configuracion->servicios->correo->adjuntoLimite);
        return (int)$response->getContent();
    }
}