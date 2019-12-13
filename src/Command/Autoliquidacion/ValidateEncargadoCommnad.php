<?php


namespace App\Command\Autoliquidacion;


use App\Command\Helpers\Loggable;

use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Repository\ConvenioRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ValidateEncargadoCommnad extends TraitableCommand
{
    use Loggable,
        SelCommandTrait;

    protected static $defaultName = "sel:autoliquidacion:validate-encargado";

    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ConvenioRepository $convenioRepository  )
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->convenioRepository = $convenioRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription("Valida que para los convenios que tienen encargado, 
            los empleados esten correctamente asignados. Los empleados sin representante asigna automaticamente al encargado")
            ->addOption('test', 't', InputOption::VALUE_NONE, 'Muestra informacion, no realiza ninguna accion');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = $input->getOption('test');

        $convenios = $this->convenioRepository->findConveniosWithRepresentante();
        foreach($convenios as $convenio) {
            $flush = false;
            if($convenio->hasEncargados()) {
                $encargados = $convenio->getEncargados();
                if($encargados->count() < 2) {
                    $this->logger->info("*** Convenio '{$convenio->getCodigo()} {$convenio->getNombre()}' tiene un encargado ***");
                    $this->logger->info($encargados[0]->getUsuario()->getIdentificacion() . " " . $encargados[0]->getUsuario()->getNombrePrimeros());
                    $empleados = $convenio->getEmpleados();
                    foreach($empleados as $empleado) {
                        if(!$empleado->getRepresentante()) {
                            $this->logger->warning("Empleado '{$empleado->getUsuario()->getIdentificacion()}' no tiene encargado asignado");
                            if(!$test) {
                                $empleado->setRepresentante($encargados[0]);
                                $flush = true;
                            }
                        }
                    }
                } else {
                    $this->logger->warning("Convenio '{$convenio->getCodigo()}' has more than one encargado");
                    foreach($encargados as $encargado) {
                        $this->logger->warning($encargado->getUsuario()->getIdentificacion() . " "
                            . $encargado->getUsuario()->getNombrePrimeros());
                    }
                }
            }
            if(!$test && $flush) {
                $this->em->flush();
            }
        }
    }
}