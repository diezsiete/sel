<?php


namespace App\Controller\Admin;


use App\DataTable\Type\Clientes\ConvenioDataTableType;
use App\DataTable\Type\ConvenioEmpleadoDataTableType;
use App\DataTable\Type\Clientes\RepresentanteDataTableType;
use App\Entity\Main\Convenio;
use App\Entity\Main\Representante;
use App\Entity\Main\Usuario;
use App\Form\ProfileFormType;
use App\Form\RepresentanteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\VarDumper\Cloner\Data;


class ConvenioController extends AbstractController
{
    /**
     * @Route("/sel/admin/convenios", name="admin_convenio_list")
     */
    public function convenios(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(ConvenioDataTableType::class, [], ['searching' => true]);

        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/convenio/list.html.twig', ['datatable' => $table]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}", name="admin_convenio")
     */
    public function convenio(Convenio $convenio)
    {
        return $this->render('admin/convenio/convenio.html.twig', ['convenio' => $convenio]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}/representantes", name="admin_convenio_representantes")
     */
    public function representantes(Convenio $convenio, DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(RepresentanteDataTableType::class, ['convenio' => $convenio], [
            'searching' => false
        ]);
        $table->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('admin/convenio/representantes.html.twig', [
            'convenio' => $convenio,
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}/representante/agregar/{nuevoRepresentante}",
     *     name="admin_convenio_representante_add", defaults={"nuevoRepresentante": null})
     */
    public function representanteAdd(Convenio $convenio, Request $request, EntityManagerInterface $em,
                                     ?Representante $nuevoRepresentante = null)
    {
        if(!$nuevoRepresentante) {
            $form = $this->createFormBuilder()->add('identificacion', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese identificacion'])
                ]])->getForm()->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                return $this->redirectToRoute('admin_convenio_representante_add', [
                    'codigo' => $convenio->getCodigo(),
                    'nuevoRepresentante' => $form['identificacion']->getData()
                ]);
            }
            $form = ['formIdentificacion' => $form->createView()];
        } else {
            $form = $this->representanteForm($nuevoRepresentante, $request, $em);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->container->get('session')->getFlashBag()->clear();
                $this->addFlash('success', "Representante asignado exitosamente!");
                return $this->redirectToRoute('admin_convenio_representantes', ['codigo' => $convenio->getCodigo()]);
            }
            $form = ['representanteForm' => $form->createView()];
        }
        return $this->render('admin/convenio/representante/add.html.twig', ['convenio' => $convenio] + $form);
    }

    /**
     * @Route("/sel/admin/convenio/{codigo}/representante/{rid}",
     *     name="admin_convenio_representante_edit",
     *     requirements={"rid"="\d+"}
     * )
     * @ParamConverter("convenio", options={"mapping": {"codigo": "codigo"}})
     * @ParamConverter("representante", options={"mapping": {"rid": "id"}})
     */
    public function representanteEdit(Convenio $convenio, Representante $representante, Request $request, EntityManagerInterface $em)
    {
        $form = $this->representanteForm($representante, $request, $em);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', "Representante actualizado exitosamente!");
            return $this->redirectToRoute('admin_convenio_representantes', ['codigo' => $convenio->getCodigo()]);
        }
        return $this->render('admin/convenio/representante/edit.html.twig', [
            'convenio' => $convenio,
            'representanteForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/sel/admin/convenio/{codigo}/empleados", name="admin_convenio_empleados")
     */
    public function empleados(Request $request, Convenio $convenio, DataTableFactory $dataTableFactory)
    {
        $datatable = $dataTableFactory->createFromType(ConvenioEmpleadoDataTableType::class, ['convenio' => $convenio], [
            'searching' => true
        ])->handleRequest($request);
        if($datatable->isCallback()) {
            return $datatable->getResponse();
        }
        return $this->render('admin/convenio/empleados.html.twig', [
            'convenio' => $convenio,
            'datatable' => $datatable
        ]);
    }


    private function representanteForm(Representante $representante, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RepresentanteFormType::class, $representante);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Representante $representante */
            $representante = $form->getData();
            $modifyAllEmails = $representante->getUsuario()->getId() && isset($form['emailTodos']) && $form['emailTodos']->getData();

            $em->persist($representante);
            if($modifyAllEmails) {
                $em->createQuery(sprintf(
                        "UPDATE %s r SET r.email = '%s' WHERE r.usuario = %d",
                        Representante::class, $representante->getEmail(), $representante->getUsuario()->getId())
                )->execute();
            }
            $em->flush();
        }
        return $form;
    }
}