<?php

namespace App\Controller;

use App\DataTable\Type\VacanteDataTableType;
use App\Entity\Vacante;
use App\Form\VacanteFormType;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VacanteAdminController extends BaseController
{

    /**
     * @Route("/admin/vacante/listado", name="admin_vacante_listado")
     */
    public function listado(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(VacanteDataTableType::class, [], ['searching' => true])
            ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('vacante_admin/listado.html.twig', ['datatable' => $table]);
    }

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
            return $this->redirectToRoute('admin_vacante_listado');
        }

        return $this->render('vacante_admin/crear.html.twig', [
            'vacanteForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/vacante/editar/{vacante}", name="admin_vacante_editar")
     */
    public function editar(Request $request, Vacante $vacante)
    {
        $form = $this->createForm(VacanteFormType::class, $vacante);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Vacante $vacante */
            $vacante = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', "Vacante actualizada exitosamente!");
            return $this->redirectToRoute('admin_vacante_listado');
        }
        return $this->render('vacante_admin/editar.html.twig', [
            'vacanteForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/vacante/borrar/{vacante}", name="admin_vacante_borrar")
     */
    public function borrar(Vacante $vacante)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($vacante);
        $em->flush();
        $this->addFlash('success', "Vacante eliminada exitosamente!");
        return $this->redirectToRoute('admin_vacante_listado');
    }

    /**
     * @Route("/admin/vacante/subnivel-select", name="admin_vacante_subnivel_select")
     * @IsGranted("ROLE_USER")
     */
    public function subnivelSelect(Request $request)
    {
        $nivel = $request->query->get('nivel');
        $vacante = (new Vacante())->setNivel($nivel ? (int)$nivel : null);

        $form = $this->createForm(VacanteFormType::class, $vacante);

        return $this->render('vacante_admin/_subnivel.html.twig', ['vacanteForm' => $form->createView()]);

    }
}