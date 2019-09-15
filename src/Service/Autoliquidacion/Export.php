<?php


namespace App\Service\Autoliquidacion;


use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Usuario;
use App\Repository\Autoliquidacion\AutoliquidacionEmpleadoRepository;
use App\Repository\RepresentanteRepository;
use Symfony\Component\Security\Core\Security;

abstract class Export
{
    /**
     * @var Security
     */
    protected $security;
    /**
     * @var RepresentanteRepository
     */
    protected $representanteRepo;
    /**
     * @var AutoliquidacionEmpleadoRepository
     */
    protected $autoliquidacionEmpleadoRepo;
    /**
     * @var FileManager
     */
    protected $fileManager;

    public function __construct(Security $security, RepresentanteRepository $representanteRepo,
                                AutoliquidacionEmpleadoRepository $autoliquidacionEmpleadoRepo, FileManager $fileManager)
    {
        $this->security = $security;
        $this->representanteRepo = $representanteRepo;
        $this->autoliquidacionEmpleadoRepo = $autoliquidacionEmpleadoRepo;
        $this->fileManager = $fileManager;
    }

    protected function getAutoliquidacionEmpleadosByRepresentante(Autoliquidacion $autoliquidacion, Usuario $usuario = null)
    {
        if(!$usuario || $this->security->isGranted(['ROLE_ADMIN'], $usuario)) {
            return $this->autoliquidacionEmpleadoRepo->findByAutoliquidacion($autoliquidacion);
        } else {
            $representante = $this->representanteRepo->findOneBy(['usuario' => $usuario, 'convenio' => $autoliquidacion->getConvenio()]);
            if($representante->isEncargado()) {
                return $this->autoliquidacionEmpleadoRepo->findByRepresentante($representante, $autoliquidacion);
            } else {
                // TODO bcc tiene acceso a todos y deberia poderse asignar por encargado tambien
                return $this->autoliquidacionEmpleadoRepo->findByAutoliquidacion($autoliquidacion);
            }
        }
    }

    /**
     * @param Autoliquidacion $autoliquidacion
     * @param Usuario|null $usuario
     * @return string
     */
    public abstract function generate(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null);

    public abstract function stream(Autoliquidacion $autoliquidacion, ?Usuario $usuario = null);
}