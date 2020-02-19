<?php

namespace App\Serializer\Normalizer;

use App\Entity\Main\Empleado;
use App\Repository\Main\EmpleadoRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EmpleadoNormalizer extends ObjectNormalizer
{
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepo;

    /**
     * @required
     */
    public function setEmpleadoRepo(EmpleadoRepository $empleadoRepo)
    {
        $this->empleadoRepo = $empleadoRepo;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Empleado::class;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if($empleado = $this->empleadoRepo->findByIdentificacion($data['usuario']['identificacion'])) {
            $context['object_to_populate'] = $empleado;
        }
        return parent::denormalize($data, $class, $format, $context);
    }
}