<?php

namespace App\Command\Migration\Halcon;

use App\Command\Helpers\ConsoleProgressBar;
use App\Command\Helpers\SelCommandTrait;
use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Halcon\Report\CertificadoLaboral as HalconCertificadoLaboral;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\CertificadoLaboral as SeCertificadoLaboral;
use App\Repository\Halcon\Report\CertificadoLaboralRepository as HalconCertificadoLaboralRepository;
use App\Repository\ServicioEmpleados\CertificadoLaboralRepository as SeCertificadoLaboralRepository;
use App\Repository\Halcon\VinculacionRepository;
use App\Repository\Main\UsuarioRepository;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CertificadoLaboralCommand extends TraitableCommand
{
    use SelCommandTrait,
        ConsoleProgressBar;

    protected static $defaultName = "sel:migration:halcon:certificado-laboral";
    /**
     * @var HalconCertificadoLaboralRepository
     */
    private $halconCertificadoLaboralRepo;
    /**
     * @var SeCertificadoLaboralRepository
     */
    private $seCertificadoLaboralRepo;
    /**
     * @var VinculacionRepository
     */
    private $vinculacionRepo;
    /**
     * @var array
     */
    private $certificadosLaborales = null;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;
    /**
     * @var Usuario|null|false
     */
    private $currentUsuario = false;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher,
                                HalconCertificadoLaboralRepository $halconCertificadoLaboralRepo,
                                SeCertificadoLaboralRepository $seCertificadoLaboralRepo,
                                VinculacionRepository $vinculacionRepo, UsuarioRepository $usuarioRepo)
    {
        parent::__construct($annotationReader, $dispatcher);
        $this->halconCertificadoLaboralRepo = $halconCertificadoLaboralRepo;
        $this->seCertificadoLaboralRepo = $seCertificadoLaboralRepo;
        $this->vinculacionRepo = $vinculacionRepo;
        $this->usuarioRepo = $usuarioRepo;
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
        $batchSize = 20;
        $i = 0;
        $certificados = $this->getCertificadosLaborales($input);

        foreach($certificados as $certificado) {
            $usuario = $this->getUsuario($certificado->identificacion);
            if(!$usuario) {
                $this->io->warning("usuario '$certificado->identificacion' not found");
            } else {
                $certificadoLaboral = $this->createCertificadoLaboral($certificado, $usuario);
                if($dbCertificadoLaboral = $this->seCertificadoLaboralRepo->findEqual($certificadoLaboral)) {
                    $this->em->remove($dbCertificadoLaboral);
                }

                $this->em->persist($certificadoLaboral);

            }
            if (($i % $batchSize) === 0) {
                $this->emFlushAndClear();
            }
            $i++;
            $this->progressBarAdvance();
        }

        $this->emFlushAndClear();
    }

    /**
     * @param InputInterface $input
     * @return HalconCertificadoLaboral[]
     */
    private function getCertificadosLaborales(InputInterface $input)
    {
        if($this->certificadosLaborales === null) {
            $this->certificadosLaborales = [];
            $identificaciones = $input->getArgument('identificaciones');
            if(!$identificaciones) {
                $identificaciones = $this->vinculacionRepo->findAllNitTerceros();
            }
            foreach($identificaciones as $identificacion) {
                $this->certificadosLaborales = array_merge(
                    $this->certificadosLaborales, $this->halconCertificadoLaboralRepo->find($identificacion));
            }
        }
        return $this->certificadosLaborales;
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->getCertificadosLaborales($input));
    }

    private function getUsuario($identificacion)
    {
        if($this->currentUsuario === false || ($this->currentUsuario && $this->currentUsuario->getIdentificacion() !== $identificacion)) {
            $this->currentUsuario = $this->usuarioRepo->findByIdentificacion($identificacion);
        }
        return $this->currentUsuario;
    }

    /**
     * @param HalconCertificadoLaboral $certificado
     * @param Usuario $usuario
     * @return SeCertificadoLaboral
     */
    private function createCertificadoLaboral(HalconCertificadoLaboral $certificado, Usuario $usuario)
    {
        return (new SeCertificadoLaboral())
            ->setFechaIngreso($certificado->fechaIngreso)
            ->setFechaRetiro($certificado->fechaRetiro)
            ->setConvenio($certificado->convenio)
            ->setSourceHalcon()
            ->setSourceId($certificado->identificacion.",".$certificado->contrato)
            ->setUsuario($usuario);
    }

    private function emFlushAndClear()
    {
        if(!$this->input->getOption('info')) {
            $this->em->flush();
            $this->em->clear();
            $this->currentUsuario = false;
        }
    }
}