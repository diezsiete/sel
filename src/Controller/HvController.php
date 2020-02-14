<?php

namespace App\Controller;

use App\DataTable\Type\Hv\EstudioDataTableType;
use App\DataTable\Type\Hv\ExperienciaDataTableType;
use App\DataTable\Type\Hv\FamiliarDataTableType;
use App\DataTable\Type\Hv\IdiomaDataTableType;
use App\DataTable\Type\Hv\RedSocialDataTableType;
use App\DataTable\Type\Hv\ReferenciaDataTableType;
use App\DataTable\Type\Hv\ViviendaDataTableType;
use App\Entity\Main\Dpto;
use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;
use App\Entity\Main\Pais;
use App\Form\EstudioFormType;
use App\Form\ExperienciaFormType;
use App\Form\FamiliarFormType;
use App\Form\HvFormType;
use App\Form\IdiomaFormType;
use App\Form\Model\HvDatosBasicosModel;
use App\Form\RedSocialFormType;
use App\Form\ReferenciaFormType;
use App\Form\ViviendaFormType;
use App\Service\Hv\HvResolver;
use App\Service\Novasoft\Api\Client\HvClient;
use App\Service\Novasoft\Api\NovasoftApiMessenger;
use App\Service\Scraper\ScraperMessenger;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @IsGranted("ASPIRANTES_MODULE", statusCode=404, message="Resource not found")
 */
class HvController extends BaseController
{
    /**
     * @var HvResolver
     */
    private $hvResolver;

    public function __construct(HvResolver $hvResolver)
    {
        $this->hvResolver = $hvResolver;
    }

    /**
     * @Route("/sel/hv/datos-basicos", name="hv_datos_basicos")
     * @IsGranted("HV_MANAGE", subject="hvResolver")
     */
    public function datosBasicos(Request $request, HvResolver $hvResolver, NovasoftApiMessenger $napiMessenger)
    {
        $hvdto = (new HvDatosBasicosModel())
            ->fillFromEntities($this->getUser(), $hvResolver->getHv());

        $form = $this->createForm(HvFormType::class, $hvdto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var HvDatosBasicosModel $hvdto */
            $hvdto = $form->getData();

            $hv = $hvdto->fillHv($hvResolver->getHv() ?? new Hv());
            $usuario = $hvdto->fillUsuario($hvResolver->getUsuario());

            $em = $this->getDoctrine()->getManager();
            if(!$hv->getId()) {
                $hv->setUsuario($usuario);
                $em->persist($hv);
            }
            $em->flush();

            // si el usuario ya esta registrado
            if($hv->getUsuario()) {
                $napiMessenger->update($hv);
            }

            $this->addFlash('success', "Datos guardados exitosamente");
            $this->redirectToRoute('hv_datos_basicos');
        }

        return $this->render('hv/datos-basicos.html.twig', [
            'hvForm' => $form->createView(),
            'hv' => $hvResolver
        ]);
    }

    /**
     * @Route("/sel/hv/estudio", name="hv_estudio")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function estudio(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, EstudioDataTableType::class,
            EstudioFormType::class, 'hv/hv-entity/estudio.html.twig');
    }

    /**
     * @Route("/sel/hv/experiencia", name="hv_experiencia")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function experiencia(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ExperienciaDataTableType::class,
            ExperienciaFormType::class, 'hv/hv-entity/experiencia.html.twig');
    }

    /**
     * @Route("/sel/hv/referencias", name="hv_referencias")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function referencias(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ReferenciaDataTableType::class,
            ReferenciaFormType::class, 'hv/hv-entity/referencias.html.twig');
    }

    /**
     * @Route("/sel/hv/redes-sociales", name="hv_redes_sociales")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function redesSociales(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, RedSocialDataTableType::class,
            RedSocialFormType::class, 'hv/hv-entity/redes-sociales.html.twig');
    }

    /**
     * @Route("/sel/hv/familiares", name="hv_familiares")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function familiares(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, FamiliarDataTableType::class,
            FamiliarFormType::class, 'hv/hv-entity/familiares.html.twig');
    }

    /**
     * @Route("/sel/hv/vivienda", name="hv_vivienda")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function vivienda(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ViviendaDataTableType::class,
            ViviendaFormType::class, 'hv/hv-entity/vivienda.html.twig');
    }

    /**
     * @Route("/sel/hv/idiomas", name="hv_idiomas")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function idiomas(Request $request, DataTableFactory $dataTableFactory, HvResolver $hvResolver)
    {
        return $this->hvEntityPage($request, $dataTableFactory, IdiomaDataTableType::class,
            IdiomaFormType::class, 'hv/hv-entity/idiomas.html.twig');
    }

    /**
     * @Route("/hv/entity/get/{entity}/{id}", defaults={"id"=null}, name="hv_entity_get")
     * @IsGranted("HV_MANAGE", subject="entity")
     */
    public function entityGet(HvEntity $entity)
    {
        return $this->json($entity, 200, [], ['groups' => ['main']]);
    }

    /**
     * @Route("/hv/entity/update/{entity}/{id}", name="hv_entity_update", defaults={"id"=null})
     * @IsGranted("HV_MANAGE", subject="entity")
     */
    public function entityUpdate(HvEntity $entity, Request $request, HvResolver $hvResolver, $formType,
                                 HvClient $hvClient, NovasoftApiMessenger $napiMessenger)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException('Invalid JSON');
        }
        $entityId = $entity->getId();

        $entity->setHv($hvResolver->getHv());
        
        $form = $this->createForm($formType, $entity);
        $form->submit($data);
        if (!$form->isValid()) {
            return $this->json(['errors' => $this->getErrorsFromForm($form)], 400);
        }

        /** @var HvEntity $entity */
        $entity = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        // si el usuario ya esta registrado
        if($entity->getHv()->getUsuario()) {
            $hvClient->putChild($entity);
//            $entityId
//                ? $napiMessenger->updateChild($entity)
//                : $napiMessenger->insertChild($entity);
        }

        return $this->json(['ok' => 1]);
    }

    /**
     * @Route("/hv/entity/delete/{entity}/{id}", name="hv_entity_delete")
     * @IsGranted("HV_MANAGE", subject="entity")
     */
    public function entityDelete(HvEntity $entity,  HvClient $hvClient, NovasoftApiMessenger $napiMessenger)
    {
        $childClass = get_class($entity);
        $hv = $entity->getHv();

        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();

        // si el usuario ya esta registrado
        if($hv && $hv->getUsuario()) {
            $napiMessenger->deleteChild($entity, $hv);
        }

        return $this->json(['ok' => 1]);
    }

    /**
     * @Route("/sel/hv/adjunto", name="hv_adjunto")
     * @IsGranted("HV_MANAGE_PERSISTED", subject="hvResolver")
     */
    public function adjunto(Request $request, HvResolver $hvResolver)
    {
        return $this->render('hv/adjunto.html.twig', [
            'hv' => $hvResolver->getHv()
        ]);
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

    protected function hvEntityPage(Request $request, DataTableFactory $dataTableFactory, string $datatableType,
                                    string $formType, string $view, array $parameters = [])
    {
        $table = $dataTableFactory->createFromType(
            $datatableType,
            ['hv' => $this->hvResolver->getHv()],
            ['searching' => false, 'paging' => false]
        );
        $table->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        $form = $this->createForm($formType);

        return $this->render($view, $parameters + ['datatable' => $table, 'form' => $form->createView()]);
    }
}
