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

class CertificadoLaboralCommand extends MigrationCommand
{

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


    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, UsuarioRepository $usuarioRepo,
                                HalconCertificadoLaboralRepository $halconCertificadoLaboralRepo,
                                SeCertificadoLaboralRepository $seCertificadoLaboralRepo,
                                VinculacionRepository $vinculacionRepo)
    {
        parent::__construct($annotationReader, $dispatcher, $usuarioRepo);
        $this->halconCertificadoLaboralRepo = $halconCertificadoLaboralRepo;
        $this->seCertificadoLaboralRepo = $seCertificadoLaboralRepo;
        $this->vinculacionRepo = $vinculacionRepo;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $certificados = $this->getCertificadosLaborales($input);

        foreach($this->batch($certificados) as $certificado) {
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
        }
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


}