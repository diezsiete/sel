<?php


namespace App\Service;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Usuario;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Repository\UsuarioRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;

class AutoliquidacionService
{
    /**
     * @var FilesystemInterface
     */
    private $privateFilesystem;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    private $autoliquidacionEmpleadoRepository;

    public function __construct(FilesystemInterface $privateUploadFilesystem, EntityManagerInterface $em,
                                UsuarioRepository $usuarioRepository, AutoliquidacionRepository $autoliquidacionRepository,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepository)
    {
        $this->privateFilesystem = $privateUploadFilesystem;
        $this->em = $em;
        $this->usuarioRepository = $usuarioRepository;
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->autoliquidacionEmpleadoRepository = $autoliquidacionEmpleadoRepository;
    }

    public function uploadPdfResource(DateTimeInterface $periodo, $ident, $resource)
    {
        $path = $this->getPdfPath($periodo, $ident);
        $this->privateFilesystem->putStream($path, $resource);

        if (is_resource($resource)) {
            fclose($resource);
        }
    }

    public function deletePdf(?DateTimeInterface $periodo = null, $ident = null)
    {
        $path = $this->getPdfPath($periodo, $ident);
        if($this->privateFilesystem->has($path)) {
            $ident ? $this->privateFilesystem->delete($path) : $this->privateFilesystem->deleteDir($path);
        }
    }

    protected function getPdfPath(?DateTimeInterface $periodo = null, $ident = null)
    {
        $path = "/autoliquidaciones/pdfs/";
        if($periodo) {
            $periodoDir = $periodo->format('Y-m');
            $path .= "/$periodoDir" . ($ident ? "/$ident.pdf" : "");
        }
        return $path;
    }


    public function createAutoliquidacion(Convenio $convenio, DateTimeInterface $periodo, ?Usuario $usuario)
    {
        $autoliquidacion = (new Autoliquidacion())
            ->setConvenio($convenio)
            ->setUsuario($usuario ? $usuario : $this->usuarioRepository->superAdmin())
            ->setPeriodo($periodo);
        $this->em->persist($autoliquidacion);
        return $autoliquidacion;
    }

    public function createAutoliquidacionEmpleado(Autoliquidacion $autoliquidacion, Empleado $empleado)
    {
        $autoliquidacionEmpleado = (new AutoliquidacionEmpleado())
            ->setEmpleado($empleado)
            ->setAutoliquidacion($autoliquidacion);
        $this->em->persist($autoliquidacionEmpleado);
        return $autoliquidacionEmpleado;
    }

    public function deleteAutoliquidacion(DateTimeInterface $periodo, $convenio = null)
    {
        $criteria = ['periodo' => $periodo];
        if($convenio) {
            $criteria['convenio'] = $convenio;
        } else {
            $this->deletePdf($periodo);
        }
        $autoliquidaciones = $this->autoliquidacionRepository->findBy($criteria);
        foreach($autoliquidaciones as $autoliquidacion) {
            if($convenio) {
                $empleadosIdentificaciones = $this->autoliquidacionRepository->getEmpleadosIdentificaciones($autoliquidacion);
                foreach($empleadosIdentificaciones as $ident) {
                    $this->deletePdf($periodo, $ident);
                }
            }
            $this->em->remove($autoliquidacion);
        }
    }

    /**
     * @param Empleado|AutoliquidacionEmpleado $empleado
     * @param DateTimeInterface|null $periodo
     */
    public function deleteAutoliquidacionEmpleado($empleado, ?DateTimeInterface $periodo = null)
    {
        if(!($empleado instanceof AutoliquidacionEmpleado)) {
            $autoliquidacionEmpleado = $this->autoliquidacionEmpleadoRepository->findByEmpleadoPeriodo($empleado, $periodo);
        } else {
            $autoliquidacionEmpleado = $empleado;
            $empleado = $autoliquidacionEmpleado->getEmpleado();
        }
        $autoliquidacion = $autoliquidacionEmpleado->getAutoliquidacion();
        $this->deletePdf($periodo, $empleado->getUsuario()->getIdentificacion());
        $autoliquidacion->removeEmpleado($autoliquidacionEmpleado);
        $autoliquidacion->calcularPorcentajeEjecucion();
    }
}