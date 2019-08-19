<?php


namespace App\Command\Migration;


use App\Command\Helpers\PeriodoOption;
use App\Command\Helpers\SelCommandTrait;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Service\AutoliquidacionService;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationAutoliquidacion extends MigrationCommand
{
    use SelCommandTrait,
        PeriodoOption;

    protected static $defaultName = "sel:migration:autoliquidacion";

    /**
     * @var Autoliquidacion
     */
    private $currentAutoliquidacion = null;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var AutoliquidacionService
     */
    private $autoliquidacionService;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ManagerRegistry $managerRegistry, AutoliquidacionService $autoliquidacionService,
                                FilesystemInterface $migrationAutoliquidacionAdapterFilesystem)
    {
        $this->periodoDescription = 'El periodo desde donde se empiza a migrar. Especifique mes en formato Y-m';

        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->filesystem = $migrationAutoliquidacionAdapterFilesystem;
        $this->autoliquidacionService = $autoliquidacionService;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Migracion de autoliquidaciones');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = $this->filesystem->listContents();
        if(!count($contents)) {
            $this->io->error("directory autoliquidaciones empty. must be a config error");
            return;
        }

        $periodoFilter = "";
        if($periodo = $this->getPeriodo($input, false)) {
            $periodoFilter = " WHERE periodo >= '" . $periodo->format('Y-m-d') . "'";
        }

        $sqlLiquidaciones = $this->addLimitToSql("SELECT a.* FROM clientes_autoliquidacion a" . $periodoFilter);

        $count = $this->countSql($sqlLiquidaciones);

        $sqlEmpleados = "
            SELECT ae.*, e.identificacion, e.email FROM clientes_autoliquidacion a
            JOIN clientes_autoliquidacion_empleado ae ON ae.clientes_autoliquidacion_id = a.id
            JOIN novasoft_empleado e ON ae.novasoft_empleado_id = e.id";

        $count += $this->countSql($this->addLimitToSql($sqlEmpleados . $periodoFilter));

        $this->initProgressBar($count);

        while ($row = $this->fetch($sqlLiquidaciones)) {
            if($object = $this->instanceAutoliquidacion($row)) {
                $this->selPersist($object);

                while($rowEmpleado = $this->fetch($sqlEmpleados . " WHERE a.id = " . $row['id'])) {
                    $autoliquidacion = $this->findAutoliquidacion($object->getId());
                    if($autoliqEmpleado = $this->instanceAutoliquidacionEmpleado($autoliquidacion, $rowEmpleado)) {
                        if($autoliqEmpleado->isExito()) {
                            if($fileResouce = $this->getPdfResource($autoliquidacion, $autoliqEmpleado)) {
                                $this->selPersist($autoliqEmpleado);

                                $this->autoliquidacionService->uploadPdfResource(
                                    $autoliquidacion->getPeriodo(),
                                    $autoliqEmpleado->getEmpleado()->getUsuario()->getIdentificacion(),
                                    $fileResouce
                                );
                            }
                        } else {
                            $this->selPersist($autoliqEmpleado);
                        }
                    }
                }
            }
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(AutoliquidacionEmpleado::class);
        $this->truncateTable(Autoliquidacion::class);
    }

    /**
     * @param $row
     * @return Autoliquidacion|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function instanceAutoliquidacion($row)
    {
        $autoliquidacion = null;
        $convenio = $this->em->getRepository(Convenio::class)->find($row['novasoft_convenio_codigo']);
        if($convenio) {
            $autoliquidacion = (new Autoliquidacion())
                ->setConvenio($convenio)
                ->setUsuario($this->getSuperAdmin())
                ->setPeriodo(\DateTime::createFromFormat('Y-m-d', $row['periodo']))
                ->setFechaEjecucion(\DateTime::createFromFormat('Y-m-d H:i:s', $row['fecha_ejecucion']));

        } else {
            $this->io->error("convenio codigo '".$row['novasoft_convenio_codigo']."' no existe");
        }
        return $autoliquidacion;
    }

    private function instanceAutoliquidacionEmpleado(Autoliquidacion $autoliquidacion, $row)
    {
        $autoliquidacionEmpleado = null;
        $empleado = $this->findEmpleado($row);
        if($empleado) {
            $autoliquidacionEmpleado = (new AutoliquidacionEmpleado())
                ->setEmpleado($empleado)
                ->setAutoliquidacion($autoliquidacion)
                ->setExito((bool)$row['proceso_exito'])
                ->setSalida($row['proceso_salida'])
                ->setCode($row['proceso_response_code']);
        } else {
            $this->io->error("empleado con identificacion '".$row['identificacion']."' no existe");
        }
        return $autoliquidacionEmpleado;
    }

    /**
     * @param $id
     * @return Autoliquidacion
     */
    private function findAutoliquidacion($id)
    {
        if(!$this->currentAutoliquidacion || $this->currentAutoliquidacion->getId() !== $id) {
            $this->currentAutoliquidacion = $this->em->getRepository(Autoliquidacion::class)->find($id);
        }
        return $this->currentAutoliquidacion;
    }

    protected function flushAndClear()
    {
        parent::flushAndClear();
        $this->superAdmin = null;
        $this->currentAutoliquidacion = null;
    }

    private function findEmpleado($row)
    {
        $empleadoRepo = $this->em->getRepository(Empleado::class);
        $empleado = $empleadoRepo->findByIdentificacion($row['identificacion']);
        if(!$empleado) {
            // try search by email
            $this->io->warning("empleado '" . $row['identificacion'] . "' not found, searching by email '" . $row['email'] . "'");
            $empleado = $empleadoRepo->findByEmail($row['email']);
            if($empleado) {
                $this->io->success("found");
            } else {
                $this->io->error("not found");
            }
        }
        return $empleado;
    }

    private function getPdfResource(Autoliquidacion $autoliquidacion, AutoliquidacionEmpleado $autoliquidacionEmpleado)
    {
        $periodo = $autoliquidacion->getPeriodo()->format('Y-m');
        $ident = $autoliquidacionEmpleado->getEmpleado()->getUsuario()->getIdentificacion();
        $path = "/$periodo/$ident.pdf";
        if($this->filesystem->has($path)) {
            return $this->filesystem->readStream($path);
        } else {
            $this->io->error("empleado '$ident' no autoliquidacion found for period '$periodo'");
            return null;
        }
    }
}