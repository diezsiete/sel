<?php


namespace App\Controller;


use App\Service\Configuracion\Configuracion;
use App\Service\DocumentosLaborales\DocumentosLaborales;
use App\Service\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{

    /**
     * @Route("/documento-laboral/{key}", name="documento_laboral")
     */
    public function documentoLaboral($key, DocumentosLaborales $documentosLaborales, UploaderHelper $uploaderHelper)
    {
        try {
            $documentoLaboral = $documentosLaborales->get($key);
            $response = new StreamedResponse(static function() use ($documentoLaboral, $uploaderHelper){
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

    /**
     * @Route("/politica-tratamiento-datos-personales", name="politica")
     */
    public function politicaTratamientoDatosPersonales()
    {
        return $this->render('web/politica-tratamiento-datos-personales.html.twig');
    }

    /**
     * @Route("/aviso-privacidad", name="aviso_privacidad")
     */
    public function avisoPrivacidad()
    {
        return $this->render('web/aviso-privacidad.html.twig');
    }

}