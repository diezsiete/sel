<?php

namespace App\Controller;

use App\DataTable\Type\Hv\EstudioDataTableType;
use App\DataTable\Type\Hv\ExperienciaDataTableType;
use App\DataTable\Type\Hv\FamiliarDataTableType;
use App\DataTable\Type\Hv\IdiomaDataTableType;
use App\DataTable\Type\Hv\RedSocialDataTableType;
use App\DataTable\Type\Hv\ReferenciaDataTableType;
use App\DataTable\Type\Hv\ViviendaDataTableType;
use App\Entity\Dpto;
use App\Entity\Estudio;
use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\Pais;
use App\Entity\Usuario;
use App\Form\EstudioFormType;
use App\Form\ExperienciaFormType;
use App\Form\FamiliarFormType;
use App\Form\HvFlow;
use App\Form\HvFormType;
use App\Form\IdiomaFormType;
use App\Form\Model\HvDatosBasicosModel;
use App\Form\RedSocialFormType;
use App\Form\ReferenciaFormType;
use App\Form\ViviendaFormType;
use App\Repository\HvRepository;
use App\Service\HvResolver;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function datosBasicos(Request $request, HvRepository $hvRepository)
    {
        $hvdto = new HvDatosBasicosModel();
        $hvdto->fillFromEntities($this->getUser(), $hvRepository->findByUsuario($this->getUser()));

        $form = $this->createForm(HvFormType::class, $hvdto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var HvDatosBasicosModel $hvdto */
            $hvdto = $form->getData();
        }
        return $this->render('hv/datos-basicos.html.twig', [
            'hvForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sel/hv/estudio", name="hv_estudio")
     */
    public function estudio(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, EstudioDataTableType::class,
            EstudioFormType::class, 'hv/hv-entity/estudio.html.twig');
    }

    /**
     * @Route("/sel/hv/experiencia", name="hv_experiencia")
     */
    public function experiencia(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ExperienciaDataTableType::class,
            ExperienciaFormType::class, 'hv/hv-entity/experiencia.html.twig');
    }

    /**
     * @Route("/sel/hv/referencias", name="hv_referencias")
     */
    public function referencias(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ReferenciaDataTableType::class,
            ReferenciaFormType::class, 'hv/hv-entity/referencias.html.twig');
    }

    /**
     * @Route("/sel/hv/redes-sociales", name="hv_redes_sociales")
     */
    public function redesSociales(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, RedSocialDataTableType::class,
            RedSocialFormType::class, 'hv/hv-entity/redes-sociales.html.twig');
    }

    /**
     * @Route("/sel/hv/familiares", name="hv_familiares")
     */
    public function familiares(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, FamiliarDataTableType::class,
            FamiliarFormType::class, 'hv/hv-entity/familiares.html.twig');
    }

    /**
     * @Route("/sel/hv/vivienda", name="hv_vivienda")
     */
    public function vivienda(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ViviendaDataTableType::class,
            ViviendaFormType::class, 'hv/hv-entity/vivienda.html.twig');
    }

    /**
     * @Route("/sel/hv/idiomas", name="hv_idiomas")
     */
    public function idiomas(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, IdiomaDataTableType::class,
            IdiomaFormType::class, 'hv/hv-entity/idiomas.html.twig');
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
    public function entityUpdate(HvEntity $entity, Request $request, HvResolver $hvResolver, $formType)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new BadRequestHttpException('Invalid JSON');
        }

        $entity->setHv($hvResolver->getHv());

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
