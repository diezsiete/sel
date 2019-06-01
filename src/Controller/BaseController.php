<?php


namespace App\Controller;


use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package App\Controller
 * @method Usuario getUser()
 */
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