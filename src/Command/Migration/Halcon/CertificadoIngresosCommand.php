<?php

namespace App\Command\Migration\Halcon;

use App\Entity\Halcon\CertificadoIngresos as HalconCertificadoIngresos;
use App\Entity\Main\Usuario;
use App\Entity\ServicioEmpleados\CertificadoIngresos as SeCertificadoIngresos;
use App\Repository\Halcon\CertificadoIngresosRepository as HalconCertificadoIngresosRepository;
use App\Repository\ServicioEmpleados\CertificadoIngresosRepository as SeCertificadoIngresosRepository;
use App\Repository\Main\UsuarioRepository;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CertificadoIngresosCommand
 * @package App\Command\Migration\Halcon
 * @method HalconCertificadoIngresos[] batch($elements)
 */
class CertificadoIngresosCommand extends MigrationCommand
{

    protected static $defaultName = "sel:migration:halcon:certificado-ingresos";

    /**
     * @var HalconCertificadoIngresosRepository
     */
    private $halconCertificadoIngresosRepo;
    /**
     * @var SeCertificadoIngresosRepository
     */
    private $seCertificadoIngresosRepo;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $dispatcher, UsuarioRepository $usuarioRepo,
                                HalconCertificadoIngresosRepository $halconCertificadoIngresosRepo,
                                SeCertificadoIngresosRepository $seCertificadoIngresosRepo)
    {
        parent::__construct($annotationReader, $dispatcher, $usuarioRepo);
        $this->halconCertificadoIngresosRepo = $halconCertificadoIngresosRepo;
        $this->seCertificadoIngresosRepo = $seCertificadoIngresosRepo;

    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $certificados = $this->halconCertificadoIngresosRepo->findByIdentificacion($input->getArgument('identificaciones'));

        foreach($this->batch($certificados) as $certificado) {
            if($certificado->getEmpresa()) {
                $usuario = $this->getUsuario($certificado->getNitTercer());
                if(!$usuario) {
                    $this->io->warning("usuario '{$certificado->getNitTercer()}' not found");
                } else {
                    $seCertificado = $this->createCertificadoIngresos($certificado, $usuario);
                    if($dbCertificado = $this->seCertificadoIngresosRepo->findEqual($seCertificado)) {
                        $this->em->remove($dbCertificado);
                    }
                    $this->em->persist($seCertificado);
                }
            }
        }
    }

    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return $this->halconCertificadoIngresosRepo->countByIdentificacion($input->getArgument('identificaciones'));
    }

    /**
     * @param HalconCertificadoIngresos $certificado
     * @param Usuario $usuario
     * @return SeCertificadoIngresos
     */
    private function createCertificadoIngresos(HalconCertificadoIngresos $certificado, Usuario $usuario)
    {
        $id = [
            $certificado->getEmpresa()->getUsuario(),
            $certificado->getNoContrat(),
            $certificado->getAno()
        ];
        return (new SeCertificadoIngresos())
            ->setPeriodo(DateTime::createFromFormat("Y-m-d", $certificado->getAno() . '-01-01'))
            ->setSourceHalcon()
            ->setSourceId(implode(",", $id))
            ->setUsuario($usuario);
    }


}