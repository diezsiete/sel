<?php


namespace App\Service\Halcon\Servicios;


use League\Flysystem\FilesystemInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Pdf extends HalconServicio
{
    public function generarHtml(string $html)
    {
        $request = $this->client->request($this->configuracion->servicios->pdf->generar->html);
        $request->body = $html;
        return $request->post()->toArray();
    }

    /**
     * @param string $html
     * @param string|FilesystemInterface $downloadToPath
     * @throws TransportExceptionInterface
     */
    public function generarHtmlStreamBorrar(string $html, $downloadToPath)
    {
        $request = $this->client->request($this->configuracion->servicios->pdf->generar->htmlLeerBorrar);
        $request->body = $html;
        $response = $request->post();
        if (200 !== $response->getStatusCode()) {
            throw new \Exception('...');
        }

        // get the response content in chunks and save them in a file
        // response chunks implement Symfony\Contracts\HttpClient\ChunkInterface
        $fileHandler = fopen($downloadToPath , 'w');
        foreach ($this->client->stream($response) as $chunk) {
            fwrite($fileHandler, $chunk->getContent());
        }
    }
}