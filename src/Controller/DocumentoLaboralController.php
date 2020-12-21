<?php


namespace App\Controller;


use App\Service\DocumentosLaborales\DocumentosLaborales;
use App\Service\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class DocumentoLaboralController extends BaseController
{
    /**
     * @Route("/documento-laboral/{key}", name="documento_laboral")
     */
    public function documentoLaboral($key, DocumentosLaborales $documentosLaborales, UploaderHelper $uploaderHelper)
    {
        try {
            $documentoLaboral = $documentosLaborales->get($key);
            return $this->renderStream(function () use ($uploaderHelper, $documentoLaboral) {
                return $uploaderHelper->readStream($documentoLaboral->getFilePath(), false);
            }, $documentoLaboral->getMimeType());
            // $documentoLaboral = $documentosLaborales->get($key);
            // $response = new StreamedResponse(static function() use ($documentoLaboral, $uploaderHelper){
            //     $outputStream = fopen('php://output', 'wb');
            //     $fileStream = $uploaderHelper->readStream($documentoLaboral->getFilePath(), false);
            //
            //     stream_copy_to_stream($fileStream, $outputStream);
            // });
            // $response->headers->set('Content-Type', $documentoLaboral->getMimeType());
            //
            // return $response;

        } catch (\Exception $e) {
            return $this->createNotFoundException();
        }
    }

    /**
     * @Route("/documento-laboral/private/{key}", name="documento_laboral_private")
     * @IsGranted("ROLE_EMPLEADO")
     */
    public function documentoLaboralPrivate($key, DocumentosLaborales $documentosLaborales, UploaderHelper $uploaderHelper)
    {
        try {
            $documentoLaboral = $documentosLaborales->get($key, false);
            return $this->renderStream(function () use ($uploaderHelper, $documentoLaboral) {
                return $uploaderHelper->readStream($documentoLaboral->getFilePath(), false);
            }, $documentoLaboral->getMimeType());
        } catch (\Exception $e) {
            return $this->createNotFoundException();
        }
    }
}