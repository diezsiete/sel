<?php

namespace App\Controller;

use App\DataTable\Type\Hv\EstudioDataTableType;
use App\DataTable\Type\Hv\ExperienciaDataTableType;
use App\DataTable\Type\Hv\FamiliarDataTableType;
use App\DataTable\Type\Hv\ReferenciaDataTableType;
use App\Form\EstudioFormType;
use App\Form\ExperienciaFormType;
use App\Form\FamiliarFormType;
use App\Form\HvFormType;
use App\Form\Model\HvDatosBasicosModel;
use App\Form\ReferenciaFormType;
use App\Service\HvResolver;
use App\Service\RegistroWizard;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    /**
     * @var RegistroWizard
     */
    private $registroWizard;

    public function __construct(RegistroWizard $registroWizard)
    {
        $this->registroWizard = $registroWizard;
    }

    /**
     * @Route("/registro", name="registro_datos_basicos")
     */
    public function index(Request $request, HvResolver $hvResolver)
    {
        $hvdto = (new HvDatosBasicosModel())
            ->fillFromEntities($this->registroWizard->getUsuario(), $this->registroWizard->getHv());
        $hvdto->identificacion = 1234567890;

        $form = $this->createForm(HvFormType::class, $hvdto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var HvDatosBasicosModel $hvdto */
            $hvdto = $form->getData();

            $hv = $hvdto->fillHv($this->registroWizard->getHv());
            $usuario = $hvdto->fillUsuario($this->registroWizard->getUsuario());

            $em = $this->getDoctrine()->getManager();
            $em->persist($hv);
            $em->flush();

            $this->registroWizard
                ->setHv($hv)
                ->setUsuario($usuario);

            return $this->redirectToRoute($this->registroWizard->nextRoute());
        }

        return $this->render('registro/datos-basicos.html.twig', [
            'hvForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/registro/estudios", name="registro_estudios")
     */
    public function estudio(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, EstudioDataTableType::class,
            EstudioFormType::class, 'registro/estudios.html.twig');
    }

    /**
     * @Route("/registro/experiencia", name="registro_experiencia")
     */
    public function experiencia(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ExperienciaDataTableType::class,
            ExperienciaFormType::class, 'registro/experiencia.html.twig');
    }

    /**
     * @Route("/registro/referencias", name="registro_referencias")
     */
    public function referencias(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, ReferenciaDataTableType::class,
            ReferenciaFormType::class, 'registro/referencias.html.twig');
    }

    /**
     * @Route("/registro/familiares", name="registro_familiares")
     */
    public function familiares(Request $request, DataTableFactory $dataTableFactory)
    {
        return $this->hvEntityPage($request, $dataTableFactory, FamiliarDataTableType::class,
            FamiliarFormType::class, 'registro/familiares.html.twig');
    }

    /**
     * @Route("/registro/cuenta", name="registro_cuenta")
     */
    public function cuenta(Request $request)
    {
        return $this->render('registro/cuenta.html.twig');
    }

    /**
     * @Route("/registro/can-next-route", name="registro_can_next_route", options={"expose" = true})
     */
    public function canNextRoute()
    {
        $ok = $this->registroWizard->canNextRoute();
        return $this->json(['can' => $ok === true, 'errorMessage' => $ok === true ? "" : $ok]);
    }


    protected function hvEntityPage(Request $request, DataTableFactory $dataTableFactory, string $datatableType,
                                    string $formType, string $view, array $parameters = [])
    {
        $table = $dataTableFactory->createFromType(
            $datatableType,
            ['hv' => $this->registroWizard->getHv()],
            ['searching' => false, 'paging' => false]
        );
        $table->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }
        $form = $this->createForm($formType);

        return $this->render($view, $parameters + [
            'datatable' => $table,
            'form' => $form->createView(),
            'prevRoute' => $this->registroWizard->prevRoute(),
            'nextRoute' => $this->registroWizard->nextRoute(),
        ]);
    }
}
