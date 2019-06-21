<?php

namespace App\Controller;

use App\Form\CandidatoFormType;
use App\Form\ContactoFormType;
use App\Form\Model\ContactoModel;
use App\Service\Mailer;
use App\Service\SelParameters;
use App\Service\UploaderHelper;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServilaborController extends AbstractController
{
    /**
     * @Route("/", name="servilabor_inicio", host="%empresa.servilabor.host%")
     */
    public function inicio()
    {
        return $this->render('servilabor/inicio.html.twig');
    }

    /**
     * @Route("/nosotros", name="servilabor_nosotros", host="%empresa.servilabor.host%")
     */
    public function nosotros()
    {
        return $this->render('servilabor/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="servilabor_servicios", host="%empresa.servilabor.host%")
     */
    public function servicios()
    {
        return $this->render('servilabor/servicios.html.twig');
    }

    /**
     * @Route("/servicios/{servicio}", name="servilabor_servicio", host="%empresa.servilabor.host%")
     */
    public function serviciosInner($servicio)
    {
        if(!in_array($servicio, ['outsourcing', 'rpo', 'payroll'])) {
            throw $this->createNotFoundException('Pagina no encontrada');
        }

        return $this->render('servilabor/servicios-inner.html.twig', [
            'servicio' => $servicio
        ]);
    }

    /**
     * @Route("/blog", name="servilabor_blog", host="%empresa.servilabor.host%")
     */
    public function blog()
    {
        return $this->render('servilabor/blog.html.twig');
    }

    /**
     * @Route("/candidatos", name="servilabor_candidatos", host="%empresa.servilabor.host%")
     */
    public function candidatos(Request $request, UploaderHelper $uploaderHelper, Mailer $mailer, ContainerBagInterface $bag, SelParameters $parameters)
    {
        $form = $this->createForm(CandidatoFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($adjunto = $request->files->get('adjunto')) {
                $data = $form->getData();
                $fileMetadata = $uploaderHelper->uploadPrivateFile($adjunto, true, $bag);

                $subject = '[servilabor.com.co/candidatos] ' . $data['nombre'];
                $from = $data['email'];
                $to = $parameters->getContactoEmail();
                $mailer->send($subject, $from, $to, 'servilabor/emails/candidatos.html.twig', [
                    'data' => $data
                ], $fileMetadata['fullpath']);

                $this->addFlash('success', 'El mensaje se ha enviado exitosamente, gracias por utilizar nuestros servicios');
            } else {
                $this->addFlash('danger', 'Por favor adjunte su hoja de vida');
            }
        }

        return $this->render('servilabor/candidatos.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/contacto", name="servilabor_contacto", host="%empresa.servilabor.host%")
     */
    public function contacto(Request $request, Mailer $mailer)
    {
        $form = $this->createForm(ContactoFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $mailer->sendContacto($form->getData());

            $this->addFlash('success', 'El mensaje se ha enviado exitosamente');
        }

        return $this->render('servilabor/contacto.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
