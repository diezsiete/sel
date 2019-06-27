<?php

namespace App\Controller;

use App\DataTable\Type\Hv\EstudioDataTableType;
use App\DataTable\Type\Hv\ExperienciaDataTableType;
use App\DataTable\Type\Hv\FamiliarDataTableType;
use App\DataTable\Type\Hv\ReferenciaDataTableType;
use App\Entity\Usuario;
use App\Form\CuentaFormType;
use App\Form\EstudioFormType;
use App\Form\ExperienciaFormType;
use App\Form\FamiliarFormType;
use App\Form\HvFormType;
use App\Form\Model\HvDatosBasicosModel;
use App\Form\ReferenciaFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\Hv\HvWizard\HvWizard;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistroController extends AbstractController
{
    /**
     * @var HvWizard
     */
    private $hvWizard;

    public function __construct(HvWizard $hvWizard)
    {
        $this->hvWizard = $hvWizard;
    }

    /**
     * @Route("/registro", name="registro_datos_basicos")
     */
    public function basicos(Request $request)
    {
        $hvdto = (new HvDatosBasicosModel())
            ->fillFromEntities($this->hvWizard->getUsuario(), $this->hvWizard->getHv());

        $form = $this->createForm(HvFormType::class, $hvdto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            /** @var HvDatosBasicosModel $hvdto */
            $hvdto = $form->getData();

            $hv = $hvdto->fillHv($this->hvWizard->getHv());
            $usuario = $hvdto->fillUsuario($this->hvWizard->getUsuario());

            $em = $this->getDoctrine()->getManager();
            $em->persist($hv);
            $em->flush();

            $this->hvWizard
                ->setHv($hv)
                ->setUsuario($usuario)
                ->setCurrentStepValid();

            $nextRoute = $this->hvWizard->nextRoute();
            return $this->redirectToRoute($nextRoute->route, $nextRoute->parameters);
        }

        return $this->render('registro/datos-basicos.html.twig', [
            'hvForm' => $form->createView(),
            'hvWizard' => $this->hvWizard,
        ]);
    }

    /**
     * @Route("/registro/estudios", name="registro_estudios")
     */
    public function estudios(RequestStack $requestStack, DataTableFactory $dataTableFactory)
    {
        if($routeInvalid = $this->hvWizard->validatePrevStepsValid()) {
            return $this->redirectToRoute($routeInvalid->route, $routeInvalid->parameters);
        }
        return $this->hvEntityPage($requestStack->getMasterRequest(), $dataTableFactory, EstudioDataTableType::class,
            EstudioFormType::class, 'registro/estudios.html.twig');
    }

    /**
     * @Route("/registro/experiencia", name="registro_experiencia")
     */
    public function experiencia(RequestStack $requestStack, DataTableFactory $dataTableFactory)
    {
        if($routeInvalid = $this->hvWizard->validatePrevStepsValid()) {
            return $this->redirectToRoute($routeInvalid->route, $routeInvalid->parameters);
        }
        return $this->hvEntityPage($requestStack->getMasterRequest(), $dataTableFactory, ExperienciaDataTableType::class,
            ExperienciaFormType::class, 'registro/experiencia.html.twig');
    }

    /**
     * @Route("/registro/referencias", name="registro_referencias")
     */
    public function referencias(RequestStack $requestStack, DataTableFactory $dataTableFactory)
    {
        if($routeInvalid = $this->hvWizard->validatePrevStepsValid()) {
            return $this->redirectToRoute($routeInvalid->route, $routeInvalid->parameters);
        }
        return $this->hvEntityPage($requestStack->getMasterRequest(), $dataTableFactory, ReferenciaDataTableType::class,
            ReferenciaFormType::class, 'registro/referencias.html.twig');
    }

    /**
     * @Route("/registro/familiares", name="registro_familiares")
     */
    public function familiares(RequestStack $requestStack, DataTableFactory $dataTableFactory)
    {
        if($routeInvalid = $this->hvWizard->validatePrevStepsValid()) {
            return $this->redirectToRoute($routeInvalid->route, $routeInvalid->parameters);
        }
        return $this->hvEntityPage($requestStack->getMasterRequest(), $dataTableFactory, FamiliarDataTableType::class,
            FamiliarFormType::class, 'registro/familiares.html.twig');
    }

    /**
     * @Route("/registro/cuenta", name="registro_cuenta")
     */
    public function cuenta(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                           GuardAuthenticatorHandler $guard, LoginFormAuthenticator $formAuthenticator)
    {
        if($routeInvalid = $this->hvWizard->validatePrevStepsValid()) {
            return $this->redirectToRoute($routeInvalid);
        }

        $usuario = $this->hvWizard->getUsuario();
        $form = $this->createForm(CuentaFormType::class, $usuario);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Usuario $usuario */
            $usuario = $form->getData();

            $plainPassword = $usuario->getPassword();
            $encodedPassword = $passwordEncoder->encodePassword($usuario, $plainPassword);
            $usuario->setPassword($encodedPassword);

            $this->hvWizard->getHv()->setUsuario($usuario);

            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            $this->addFlash('success', "Registrado exitosamente!");

            $this->hvWizard->clearSession();
            return $guard->authenticateUserAndHandleSuccess($usuario, $request, $formAuthenticator, 'main');
        }

        return $this->render('registro/cuenta.html.twig', [
            'cuentaForm' => $form->createView(),
            'hv' => $this->hvWizard->getHv(),
            'prevRoute' => $this->hvWizard->prevRoute(),
            'hvWizard' => $this->hvWizard,
        ]);
    }

    /**
     * @Route("/registro/can-next-route", name="registro_can_next_route", options={"expose" = true})
     */
    public function canNextRoute()
    {
        $ok = $this->hvWizard->canNextRoute();
        if($ok === true) {
            $this->hvWizard->setCurrentStepValid();
        } else {
            $this->hvWizard->setCurrentStepInvalid();
        }
        return $this->json(['can' => $ok === true, 'errorMessage' => $ok === true ? "" : $ok]);
    }


    protected function hvEntityPage(Request $request, DataTableFactory $dataTableFactory, string $datatableType,
                                    string $formType, string $view, array $parameters = [])
    {
        $table = $dataTableFactory->createFromType(
            $datatableType,
            ['hv' => $this->hvWizard->getHv()],
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
            'prevRoute' => $this->hvWizard->prevRoute(),
            'nextRoute' => $this->hvWizard->nextRoute(),
            'hvWizard' => $this->hvWizard,
        ]);
    }
}
