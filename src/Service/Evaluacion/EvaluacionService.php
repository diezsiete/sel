<?php


namespace App\Service\Evaluacion;


use App\Entity\Evaluacion\Progreso;
use App\Entity\Main\Usuario;
use App\Repository\Evaluacion\ProgresoRepository;

class EvaluacionService
{
    /**
     * @var ProgresoRepository
     */
    private $progresoRepo;

    public function __construct(ProgresoRepository $progresoRepo)
    {
        $this->progresoRepo = $progresoRepo;
    }

    /**
     * @param Usuario $usuario
     * @param null $evaluacion
     * @return Progreso|null
     */
    public function usuarioHasEvaluacion(Usuario $usuario, $evaluacion = null)
    {
        return $this->progresoRepo->findByUsuario($usuario);
    }
}