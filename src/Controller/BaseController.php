<?php


namespace App\Controller;


use App\Entity\Usuario;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $errors = array();
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                foreach($childForm->getErrors() as $error) {
                    $errors[$childForm->getName()] = $error->getMessage();
                }
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
}