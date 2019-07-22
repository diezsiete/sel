<?php


namespace App\Command\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Convenio;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Repository\UsuarioRepository;
use App\Service\AutoliquidacionService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AutoliquidacionCreateCommand extends AutoliquidacionCommand
{
    protected static $defaultName = 'sel:autoliquidacion:create';

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var AutoliquidacionService
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


    public function __construct(UsuarioRepository $usuarioRepository, AutoliquidacionService $autoliquidacionService,
                                AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepository)
    {
        parent::__construct();
        $this->usuarioRepository = $usuarioRepository;
        $this->autoliquidacionService = $autoliquidacionService;
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionEmpleadoRepository = $autoliquidacionEmpleadoRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOptionRequired('periodo', 'p', InputOption::VALUE_REQUIRED,
            'Especifique mes en formato Y-m')
            ->addOption('not_overwrite', null, InputOption::VALUE_NONE,
                'Si la autoliquidacion ya existe la borra')
            ->addSearchByConvenioOrIdent();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rango = $this->getRangoFromPeriodo($input->getOption('periodo'));
        $overwrite = !$input->getOption('not_overwrite');

        if($this->isSearchConvenio()) {
            $conveniosCodigos = $this->getConveniosCodigos();
            if($overwrite) {
                $this->autoliquidacionService->deleteAutoliquidacion($rango->start, $this->searchValue);
            }
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

//    protected function xxxx()
//    {
//        if($overwrite) {
//            $this->autoliquidacionService->deleteAutoliquidacion($periodo, $convenio);
//        } else {
//            $oldAutoliquidacion = $this->autoliquidacionRepository->findBy(['convenio' => $convenio, 'periodo' => $periodo]);
//            if($oldAutoliquidacion) {
//                throw new \Exception(sprintf("Autoliquidacion del periodo '%s' para el convenio '%s' ya existe"),
//                    $periodo->format('Y-m'), $convenio->getCodigo());
//            }
//        }
//
//    }

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
            ->setUsuario($usuario ? $usuario : $this->usuarioRepository->superAdmin())
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
        $rango = $this->getRangoFromPeriodo($input->getOption('periodo'));
        return $this->empleadoRepository->countByRango($rango->start, $rango->end, $this->searchValue);
    }

    protected function getRangoFromPeriodo($periodo)
    {
        $start = \DateTime::createFromFormat('Y-m-d', "$periodo-01");
        $end = \DateTime::createFromFormat('Y-m-d', $start->format('Y-m-t'));
        return (object)['start' => $start, 'end' => $end];
    }
}