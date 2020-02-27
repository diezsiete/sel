<?php


namespace App\Controller\Clientes;


use App\Controller\BaseController;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\Clientes\TrabajadoresActivosDataTableType;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use App\Repository\Main\ConvenioRepository;
use App\Repository\Novasoft\Report\LiquidacionNomina\LiquidacionNominaRepository;
use App\Service\PortalClientes\PortalClientesService;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class Clientes
 * @package App\Controller\Clientes
 * TODO seguridad solo a roles pertinentes
 */
class ClientesController extends BaseController
{
    /**
     * @var PortalClientesService
     */
    private $portalClientesService;

    public function __construct(PortalClientesService $portalClientesService)
    {
        $this->portalClientesService = $portalClientesService;
    }

    /**
     * @Route("/sel/clientes/trabajadores-activos", name="clientes_trabajadores_activos")
     */
    public function trabajadoresActivos(DataTableFactory $dataTableFactory, Request $request)
    {
        $datatableOptions = [];
        if($convenio = $this->portalClientesService->getRepresentanteConvenio()) {
            $datatableOptions['convenio'] = $convenio;
        }
        $datatable = $dataTableFactory
            ->createFromType(TrabajadoresActivosDataTableType::class, $datatableOptions, ['searching' => true])
            ->handleRequest($request);

        if($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('/clientes/trabajadores-activos.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @Route("/sel/clientes/empleado/{id}", name="clientes_trabajador_activo_detalle")
     */
    public function trabajadorActivo(TrabajadorActivo $trabajadorActivo, LiquidacionNominaRepository $repository,
                                     DataTableFactory $dataTableFactory, Request $request,
                                     ConvenioRepository $convenioRepo, NormalizerInterface $normalizer)
    {
        $liquidacionesNomina = $repository->findBy(['empleado' => $trabajadorActivo->getEmpleado()]);

        $table = $dataTableFactory
            ->createFromType(AutoliquidacionEmpleadoDataTableType::class, [
                'empleado' => $trabajadorActivo->getEmpleado()
            ], ['searching' => true])
            ->handleRequest($request);
        if($table->isCallback()) {
            return $table->getResponse();
        }

        $empleado = $normalizer->normalize($trabajadorActivo, 'json', ['groups' =>['api']])['empleado'];

        $convenio = $convenioRepo->find('PTASAS0001');
        $isAdmin = $this->getUser()->esRol(['/ADMIN/', '/SERVICIO/']);
        return $this->render('/clientes/trabajador-activo-detalle.html.twig', [
            'trabajadorActivo' => $trabajadorActivo,
            'liquidacionesNomina' => $liquidacionesNomina,
            'datatable' => $table,
            'convenio' => $convenio,
            'isAdmin'  => $isAdmin,
            'empleado' => $empleado
        ]);
    }

    /**
     * @Route("/sel/clientes/listado-nomina2", name="clientes_listado_nomina2")
     */
    public function listadoNomina(ConvenioRepository $convenioRepo)
    {
        $convenio = $convenioRepo->find('INDMIL');
        $isAdmin = $this->getUser()->esRol(['/ADMIN/', '/SERVICIO/']);
        return $this->render('/clientes/listado-nomina/listado.html.twig', [
            'convenio' => $convenio,
            'isAdmin'  => $isAdmin,
        ]);
    }

    /**
     * @Route("/sel/clientes/{etc}", name="clientes", defaults={"etc":null}, requirements={"etc":".*"})
     */
    public function clientes(PortalClientesService $portalClientesService, ConvenioRepository $convenioRepo)
    {
        $user = $this->getUser();
        $convenio = $portalClientesService->getConvenio($user);
        $isAdmin = $user->esRol(['/ADMIN/', '/SERVICIO/']);
        $convenio = $convenioRepo->find('INDMIL');
        return $this->render('/clientes/clientes.html.twig', [
            'convenio' => $convenio,
            'isAdmin'  => $isAdmin
        ]);
    }
}