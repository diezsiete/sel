<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{

    /**
     * @param $pdfContent
     * @return Response
     */
    public function renderPdf($pdfContent)
    {
        ob_start();
        echo $pdfContent;
        return new Response(ob_get_clean(), Response::HTTP_OK,
            array('content-type' => 'application/pdf'));
    }
}