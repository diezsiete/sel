<?php

namespace App\Command\Migration\Halcon;


use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\Nomina;
use App\Repository\Halcon\VinculacionRepository;
use App\Repository\Main\UsuarioRepository;
use App\Repository\ServicioEmpleados\NominaRepository;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NominaCommand extends TraitableCommand
{
    use ConsoleProgressBar,
        SelCommandTrait;

    protected static $defaultName = "sel:migration:halcon:nomina";
    /**
     * @var VinculacionRepository
     */
    private $vinculacionRepo;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var NominaRepository
     */
    private $nominaRepository;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                VinculacionRepository $vinculacionRepo, UsuarioRepository $usuarioRepository,
                                NominaRepository $nominaRepository)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->vinculacionRepo = $vinculacionRepo;
        $this->usuarioRepository = $usuarioRepository;
        $this->nominaRepository = $nominaRepository;
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('identificaciones', InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
            'Importar identificaciones especificas')
            ->addOption('info', 'i', InputOption::VALUE_NONE, 'Muestra info, no hace nada');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $info = $input->getOption('info');
        $identificaciones = $input->getArgument('identificaciones');
        if(!$identificaciones) {
            $identificaciones = $this->vinculacionRepo->findAllNitTerceros();
        }
        foreach($identificaciones as $ident) {
            $usuario = $this->usuarioRepository->findByIdentificacion($ident);
            if(!$usuario) {
                $this->io->warning("usuario '$ident' not found");
            }
            $comprobantes = $this->vinculacionRepo->findComprobantesByIdent($ident);
            foreach ($comprobantes as $comprobante) {
                if($usuario) {
                    $selNomina = $this->createNomina($comprobante, $usuario);
                    if($selNomina && !$info) {
                        if($dbSelNomina = $this->nominaRepository->findEqual($selNomina)) {
                            $this->em->remove($dbSelNomina);
                        }
                        $this->em->persist($selNomina);
                    }
                }
                $this->progressBarAdvance();
            }
            if(!$info) {
                $this->em->flush();
                $this->em->clear();
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        $identificaciones = $input->getArgument('identificaciones');
        return $this->vinculacionRepo->countAllDistinctComprobantes($identificaciones);
    }

    private function createNomina($halconComprobante, Usuario $usuario)
    {
        if($halconComprobante['fecha'] === 'undefined') {
            return null;
        }
        return (new Nomina())
            ->setFecha(DateTime::createFromFormat('Y-m-d', $halconComprobante['fecha']))
            ->setConvenio($halconComprobante['empresa'] ? $halconComprobante['empresa'] : $halconComprobante['compania'])
            ->setSourceHalcon()
            ->setUsuario($usuario)
            ->setSourceId("{$halconComprobante['contrato']},{$halconComprobante['consecutivo']},{$halconComprobante['nitTercer']}");
    }

};