<?php

namespace App\Controller;

use App\DataTable\Type\Hv\EstudioDataTableType;
use App\DataTable\Type\Hv\ExperienciaDataTableType;
use App\Entity\Dpto;
use App\Entity\Estudio;
use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\Pais;
use App\Entity\Usuario;
use App\Form\EstudioFormType;
use App\Form\ExperienciaFormType;
use App\Form\HvEntityFormType;
use App\Form\HvFormType;
use App\Repository\HvRepository;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HvController extends BaseController
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
    public function formacion(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(EstudioDataTableType::class,
            ['usuario' => $this->getUser()], ['searching' => false, 'paging' => false])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }

        $form = $this->createForm(EstudioFormType::class);

        return $this->render('hv/estudio.html.twig', ['datatable' => $table, 'estudioForm' => $form->createView()]);
    }

    /**
     * @Route("/hv/experiencia", name="hv_experiencia")
     */
    public function experiencia(DataTableFactory $dataTableFactory, Request $request)
    {
        $table = $dataTableFactory->createFromType(ExperienciaDataTableType::class,
            ['usuario' => $this->getUser()], ['searching' => false, 'paging' => false])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }

        $form = $this->createForm(ExperienciaFormType::class);

        return $this->render('hv/experiencia.html.twig', ['datatable' => $table, 'experienciaForm' => $form->createView()]);
    }

    /**
     * @Route("/hv/entity/get/{entity}/{id}", defaults={"id"=null}, name="hv_entity_get")
     */
    public function entityGet(HvEntity $entity)
    {
        return $this->json($entity, 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/hv/entity/update/{entity}/{id}", name="hv_entity_update", defaults={"id"=null})
     */
    public function entityUpdate(HvEntity $entity, Request $request, HvRepository $hvRepository, $formType)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException('Invalid JSON');
        }

        if(!$entity->getId()) {
            $entity->setHv($hvRepository->findByUsuario($this->getUser()));
        }

        $form = $this->createForm($formType, $entity);
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json(['errors' => $this->getErrorsFromForm($form)], 400);
        }

        $estudio = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($estudio);
        $em->flush();

        return $this->json(['ok' => 1]);
    }

    /**
     * @Route("/hv/entity/delete/{entity}/{id}", name="hv_entity_delete")
     */
    public function deleteEstudio(HvEntity $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        return $this->json(['ok' => 1]);
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
