<?php


namespace App\Command\NovasoftApi\Import\Report\ServicioEmpleados;


use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Empleado;
use App\Entity\Novasoft\Report\ServicioEmpleados\CertificadoIngresos;
use App\Service\Novasoft\Api\Client\NapiClient;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CertificadoIngresosImportCommand extends TraitableCommand
{
    use SearchByConvenioOrEmpleado;

    protected static $defaultName = 'sel:napi:import:certificado-ingresos';
    /**
     * @var NapiClient
     */
    private $napiClient;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, NapiClient $napiClient)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->napiClient = $napiClient;
    }

    protected function configure()
    {
        $this->setDescription('importar certificado ingresos desde napi')
            ->addArgument('year',InputArgument::REQUIRED);
        parent::configure();
    }

    /**
     * @param string[] $conveniosCodigos
     * @return mixed
     */
    protected function executeConvenios($conveniosCodigos)
    {

    }

    /**
     * @param Empleado|Empleado[]|null
     * @return mixed
     */
    protected function executeEmpleados($empleados)
    {
        $empleados = is_array($empleados) ? $empleados : [$empleados];
        foreach($empleados as $empleado) {
            $certificadoIngresos = $this->napiClient->itemOperations(CertificadoIngresos::class)->get(
                $empleado->getUsuario()->getIdentificacion(),
                $this->input->getArgument('year') . '-01-01',
                $this->input->getArgument('year') . '-12-31'
            );
            dump($certificadoIngresos);
        }
    }
}