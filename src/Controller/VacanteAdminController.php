<?php

namespace App\Controller;

use App\Entity\Vacante;
use App\Form\VacanteFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacanteAdminController extends BaseController
{
    /**
     * @Route("/admin/vacante/crear", name="admin_vacante_crear")
     */
    public function crear(Request $request)
    {
        $vacante = new Vacante();
        $form = $this->createForm(VacanteFormType::class, $vacante);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Vacante $vacante */
            $vacante = $form->getData();
            $vacante->setUsuario($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($vacante);
            $em->flush();
            $this->addFlash('success', "Vacante creada exitosamente!");
        }

        return $this->render('vacante_admin/crear.html.twig', [
            'vacanteForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/vacante/subnivel-select", name="admin_vacante_subnivel_select")
     * @IsGranted("ROLE_USER")
     */
    public function subnivelSelect(Request $request)
    {
        $vacante = new Vacante();
        $nivel = $request->query->get('nivel');
        $vacante->setNivel($nivel ? (int)$nivel : null);

        $form = $this->createForm(VacanteFormType::class, $vacante);

        //no field? Return empty response
        if(!$form->has('subnivel')) {
            return new Response(null, 204);
        }

        // retornamos el html del campo
        return $this->render('vacante_admin/_subnivel.html.twig', [
            'vacanteForm' => $form->createView()
        ]);

    }
}
