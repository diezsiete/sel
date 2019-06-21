<?php

namespace App\Controller;

use App\Form\CandidatoFormType;
use App\Service\UploaderHelper;
use Swift_Attachment;
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
     * @Route("/candidatos", name="servilabor_candidatos", host="%empresa.servilabor.host%")
     */
    public function candidatos(Request $request, UploaderHelper $uploaderHelper, \Swift_Mailer $mailer, ContainerBagInterface $bag)
    {
        $form = $this->createForm(CandidatoFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($adjunto = $request->files->get('adjunto')) {
                $data = $form->getData();
                $fileName = $uploaderHelper->uploadPrivateFile($adjunto);
                $fileMetadata = $uploaderHelper->getPrivateFileMetadata($fileName, $bag);

                $message = (new \Swift_Message('[servilabor.com.co/candidatos] ' . $data['nombre']))
                    ->setFrom($data['email'])
                    ->setTo('guerrerojosedario@gmail.com')
                    ->setBody(
                        $this->renderView('servilabor/emails/candidatos.html.twig', [
                            'data' => $data,
                        ]), 'text/html')
                    ->attach(Swift_Attachment::fromPath($fileMetadata['fullpath']));

                $mailer->send($message);

                $this->addFlash('success', 'El mensaje se ha enviado exitosamente, gracias por utilizar nuestros servicios');
            } else {
                $this->addFlash('danger', 'Por favor adjunte su hoja de vida');
            }
        }

        return $this->render('servilabor/candidatos.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
