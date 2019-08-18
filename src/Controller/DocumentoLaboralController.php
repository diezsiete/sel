<?php


namespace App\Controller;

use App\Service\Configuracion\Configuracion;
use App\Service\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class DocumentoLaboralController extends AbstractController
{
    /**
     * @Route("/documento-laboral/{key}", name="documento_laboral")
     */
    public function documentoLaboral($key, Configuracion $configuracion, UploaderHelper $uploaderHelper)
    {
        try {
            $documentoLaboral = $configuracion->getDocumentosLaborales($key);
            $response = new StreamedResponse(function() use ($documentoLaboral, $uploaderHelper){
                $outputStream = fopen('php://output', 'wb');
                $fileStream = $uploaderHelper->readStream($documentoLaboral->getFilePath(), false);

                stream_copy_to_stream($fileStream, $outputStream);
            });
            $response->headers->set('Content-Type', $documentoLaboral->getMimeType());

            return $response;

        } catch (\Exception $e) {
            return $this->createNotFoundException();
        }
    }
}