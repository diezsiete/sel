<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\Loggable;
use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SearchByConvenioOrEmpleado;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Convenio;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Service\Autoliquidacion\DatabaseActions;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AutoliquidacionCreateCommand extends TraitableCommand
{
    use Loggable,
        PeriodoOption,
        ConsoleProgressBar,
        SelCommandTrait;

    use SearchByConvenioOrEmpleado {
        getConveniosCodigos as getConvenioCodigosTrait;
    }


    protected static $defaultName = 'sel:autoliquidacion:create';


    /**
     * @var DatabaseActions
     */
    private $autoliquidacionService;
    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    private $autoliquidacionEmpleadoRepository;



    public function __construct(Reader $reader, EventDispatcherInterface $dispatcher,
                                DatabaseActions $autoliquidacionService,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepository)
    {
        $this->periodoDescription = 'Especifique mes en formato Y-m. Sin especificar genera periodo segun fecha hoy';

        parent::__construct($reader, $dispatcher);
        $this->autoliquidacionService = $autoliquidacionService;
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionEmpleadoRepository = $autoliquidacionEmpleadoRepository;
    }

    protected function configure()
    {
        //$this->addOption('not_overwrite', null, InputOption::VALUE_NONE, 'Si la autoliquidacion ya existe la borra');
        $this->addOption('representante', 'r', InputOption::VALUE_NONE,
            'Si no se filtra por convenio, este selecciona unicamente los convenio con representantes (enviar email)');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $rango = $this->getRangoFromPeriodo($input, false);


        if($this->isSearchConvenio()) {
            $conveniosCodigos = $this->getConveniosCodigos();
            /*if($overwrite) {
                $this->autoliquidacionService->deleteAutoliquidacion($rango->start, $this->searchValue);
            }*/
            $this->autoliquidacionService->deleteAutoliquidacion($rango->start, $this->searchValue);

            foreach($conveniosCodigos as $codigo) {
                $empleados = $this->empleadoRepository->findByRangoPeriodo($rango->start, $rango->end, $codigo);

                if(count($empleados)) {
                    $autoliquidacion = $this->createAutoliquidacion($codigo, $rango->start);
                    foreach ($empleados as $empleado) {
                        $this->createAutoliquidacionEmpleado($autoliquidacion, $empleado);
                        $this->progressBarAdvance();
                    }
                    $this->em->flush();
                    $this->em->clear();
                }
            }
        } else {
            foreach($this->getEmpleados() as $empleado) {
                $autoliquidacionEmpleado = $this->autoliquidacionEmpleadoRepository->findByEmpleadoPeriodo($empleado, $rango->start);
                if($autoliquidacionEmpleado) {
                    $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();
                    $this->autoliquidacionService->deleteAutoliquidacionEmpleado($autoliquidacionEmpleado);
                    $this->createAutoliquidacionEmpleado($autoliquidacion, $empleado, true);

                } else {
                    $convenio = $empleado->getConvenio();
                    $autoliquidacion = $this->autoliquidacionRepository->findByEmpleado($empleado, $rango->start);
                    if(!$autoliquidacion) {
                        $autoliquidacion = $this->createAutoliquidacion($convenio, $rango->start);
                    }
                    $this->createAutoliquidacionEmpleado($autoliquidacion, $empleado, true);

                }
            }
            $this->em->flush();
            $this->em->clear();
        }
    }

    /**
     * @param string|Convenio $convenio codigo o objeto
     * @param \DateTimeInterface $periodo
     * @param null $usuario
     * @return Autoliquidacion
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    protected function createAutoliquidacion($convenio, \DateTimeInterface $periodo, $usuario = null)
    {
        if(!is_object($convenio)) {
            $convenio = $this->em->getReference(Convenio::class, $convenio);
        }
        $autoliquidacion = (new Autoliquidacion())
            ->setConvenio($convenio)
            ->setUsuario($usuario ? $usuario : $this->getSuperAdmin(false))
            ->setPeriodo($periodo);
        $this->em->persist($autoliquidacion);
        return $autoliquidacion;
    }

    /**
     * @param Autoliquidacion $autoliquidacion
     * @param $empleado
     * @param bool $calcularPorcentajeEjecucion
     * @return AutoliquidacionEmpleado
     */
    protected function createAutoliquidacionEmpleado($autoliquidacion, $empleado, $calcularPorcentajeEjecucion = false)
    {
        $autoliquidacionEmpleado = (new AutoliquidacionEmpleado())
            ->setEmpleado($empleado)
            ->setAutoliquidacion($autoliquidacion);
        $this->em->persist($autoliquidacionEmpleado);

        if($calcularPorcentajeEjecucion) {
            $autoliquidacion->calcularPorcentajeEjecucion();
        }

        return $autoliquidacionEmpleado;
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): int
    {
        $rango = $this->getRangoFromPeriodo($input, false);
        return $this->empleadoRepository->countByRango($rango->start, $rango->end, $this->searchValue);
    }


    protected function getConveniosCodigos()
    {
        if($this->input->getOption('representante')) {
            return $this->convenioRepository->findCodigosWithRepresentante();
        } else {
            return $this->getConvenioCodigosTrait();
        }
    }

}