<?php

namespace App\Controller;

use App\Form\ContactoFormType;
use App\Service\Configuracion\Configuracion;
use App\Service\Configuracion\Oficina;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PtaController extends AbstractController
{
    /**
     * @Route("/", name="pta_index", host="%empresa.PTA.host%")
     */
    public function index()
    {
        return $this->render('pta/index.html.twig', [
            'controller_name' => 'PtaController',
        ]);
    }

    /**
     * @Route("/nosotros", name="pta_nosotros", host="%empresa.PTA.host%")
     * @return Response
     */
    public function nosotros()
    {
        return $this->render('pta/nosotros.html.twig');
    }

    /**
     * @Route("/servicios", name="pta_servicios", host="%empresa.PTA.host%")
     */
    public function servicios()
    {
        return $this->render('pta/servicios.html.twig');
    }

    /**
     * @Route("/noticias", name="pta_noticias", host="%empresa.PTA.host%")
     */
    public function noticias()
    {
        return $this->render('pta/noticias.html.twig');
    }

    /**
     * @Route("/contacto/{oficina}", name="pta_contacto", host="%empresa.PTA.host%", defaults={"oficina": "bogota"})
     */
    public function contacto(Oficina $oficina, Request $request, Mailer $mailer, Configuracion $configuracion)
    {
        $to = $configuracion->getEmails()->getContacto();
        if(!$oficina->isPrincipal()) {
            $to = ['Mensaje a agencia ' . $oficina->getCiudad() => $oficina->getEmail()] + $to;
        }
        $form = $this->createForm(ContactoFormType::class, null, [
            'to' => $to,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $mailer->sendContacto($form->getData());
            $this->addFlash('success', 'El mensaje se ha enviado exitosamente');
            return $this->redirectToRoute('pta_contacto', ['oficina' => $oficina->getNombre()]);
        }

        return $this->render('pta/contacto.html.twig', [
            'currentOficina' => $oficina,
            'form' => $form->createView(),
        ]);
    }
}
