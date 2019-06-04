<?php

namespace App\Controller;

use App\Entity\Dpto;
use App\Entity\Hv;
use App\Entity\Pais;
use App\Form\HvFormType;
use Proxies\__CG__\App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HvController extends AbstractController
{
    /**
     * @Route("/hv/crear", name="hv_crear")
     */
    public function crear(Request $request)
    {

        $form = $this->createForm(HvFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Hv $hv */
            $hv = $form->getData();

            $primerNombre = $form['primerNombre']->getData();
            $segundoNombre = $form['segundoNombre']->getData();
            $primerApellido = $form['primerApellido']->getData();
            $segundoApellido = $form['segundoApellido']->getData();
            $identificacion = $form['identificacion']->getData();
            $email = $form['email']->getData();

            $usuario = new Usuario();
            $usuario->setPrimerNombre($primerNombre)
                ->setSegundoNombre($segundoNombre)
                ->setPrimerApellido($primerApellido)
                ->setSegundoApellido($segundoApellido)
                ->setIdentificacion($identificacion)
                ->setEmail($email)
                ->aceptarTerminos();


        }
        return $this->render('hv/crear.html.twig', [
            'hvForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/hv/datos-basicos", name="hv_datos_basicos")
     */
    public function datosBasicos(Request $request)
    {
        $form = $this->createForm(HvFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var Hv $hv */
            $hv = $form->getData();

            $primerNombre = $form['primerNombre']->getData();
            $segundoNombre = $form['segundoNombre']->getData();
            $primerApellido = $form['primerApellido']->getData();
            $segundoApellido = $form['segundoApellido']->getData();
            $identificacion = $form['identificacion']->getData();
            $email = $form['email']->getData();

            $usuario = new Usuario();
            $usuario->setPrimerNombre($primerNombre)
                ->setSegundoNombre($segundoNombre)
                ->setPrimerApellido($primerApellido)
                ->setSegundoApellido($segundoApellido)
                ->setIdentificacion($identificacion)
                ->setEmail($email)
                ->aceptarTerminos();


        }
        return $this->render('hv/datos-basicos.html.twig', [
            'hvForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/hv/formacion", name="hv_formacion")
     */
    public function formacion(Request $request)
    {
        return $this->render('hv/formacion.html.twig');
    }

    /**
     * @Route("/hv/experiencia", name="hv_experiencia")
     */
    public function experiencia(Request $request)
    {
        return $this->render('hv/experiencia.html.twig');
    }

    /**
     * @Route("/hv/referencias", name="hv_referencias")
     */
    public function referencias(Request $request)
    {
        return $this->render('hv/referencias.html.twig');
    }

    /**
     * @Route("/hv/redes-sociales", name="hv_redes_sociales")
     */
    public function redesSociales(Request $request)
    {
        return $this->render('hv/redes-sociales.html.twig');
    }

    /**
     * @Route("/hv/familiares", name="hv_familiares")
     */
    public function familiares(Request $request)
    {
        return $this->render('hv/familiares.html.twig');
    }

    /**
     * @Route("/hv/vivienda", name="hv_vivienda")
     */
    public function vivienda(Request $request)
    {
        return $this->render('hv/vivienda.html.twig');
    }

    /**
     * @Route("/hv/idiomas", name="hv_idiomas")
     */
    public function idiomas(Request $request)
    {
        return $this->render('hv/idiomas.html.twig');
    }

    /**
     * @Route("/hv/adjunto", name="hv_adjunto")
     */
    public function adjunto(Request $request)
    {
        return $this->render('hv/adjunto.html.twig');
    }

    /**
     * @Route("/hv/json/dpto/{pais}", name="hv_json_dpto", options={"expose" = true})
     */
    public function jsonDpto(Pais $pais)
    {
        $dptos = $pais->getDptos();
        return $this->json($dptos, 200, [], [
            'groups' => ['main']
        ]);
    }

    /**
     * @Route("/hv/json/ciudad/{dpto}", name="hv_json_ciudad", options={"expose" = true})
     */
    public function jsonCiudad(Dpto $dpto)
    {
        $ciudades = $dpto->getCiudades();
        return $this->json($ciudades, 200, [], [
            'groups' => ['main']
        ]);
    }
}
