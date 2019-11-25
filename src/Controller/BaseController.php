<?php


namespace App\Controller;


use App\Entity\Usuario;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Returns an associative array of validation errors
     *
     * {
     *     'firstName': 'This value is required',
     *     'subForm': {
     *         'someField': 'Invalid value'
     *     }
     * }
     *
     * @param FormInterface $form
     * @return array|string
     */
    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                foreach($childForm->getErrors() as $error) {
                    $errors[$childForm->getName()] = $error->getMessage();
                }
            }
        }

        if(!$errors) {
            foreach($form->getErrors() as $error) {
                $errors[$form->getName()] = $error->getMessage();
            }
        }

        return $errors;
    }

    /**
     * @return ObjectManager
     */
    protected function em()
    {
        return $this->getDoctrine()->getManager();
    }


    protected function renderStream($callbackStream, $contentType = 'application/pdf', $dispositionAttachment = null)
    {
        $response = new StreamedResponse(function() use ($callbackStream){
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $callbackStream();
            stream_copy_to_stream($fileStream, $outputStream);
        });
        $response->headers->set('Content-Type', $contentType);

        if($dispositionAttachment) {
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT, $dispositionAttachment);
            $response->headers->set('Content-Disposition', $disposition);
        }

        return $response;
    }

    protected function renderZip($filePath, $fileName)
    {
        ob_start();
        readfile($filePath);
        return new Response(ob_get_clean(), Response::HTTP_OK,[
            "Pragma" =>  "public",
            "Expires: 0",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Content-Description" => "File Transfer",
            "Content-type" => "application/octet-stream",
            "Content-Disposition" => "attachment; filename=\"" . $fileName . "\"",
            "Content-Transfer-Encoding" => "binary",
            "Content-Length" => filesize($filePath)
        ]);
    }
}