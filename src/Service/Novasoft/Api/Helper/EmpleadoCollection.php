<?php


namespace App\Service\Novasoft\Api\Helper;


use App\Entity\Main\Empleado;
use App\Service\Novasoft\Api\Helper\Hydra\HydraCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class EmpleadoCollection
 * @package App\Service\Novasoft\Api\Helper
 * @deprecated
 */
class EmpleadoCollection extends HydraCollection
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;
    /**
     * @var string
     */
    private $napiDb;

    public function __construct($client, $currentResponse, DenormalizerInterface $denormalizer, string $napiDb)
    {
        parent::__construct($client, $currentResponse);
        $this->denormalizer = $denormalizer;
        $this->napiDb = $napiDb;
    }

    public function current()
    {
        $data = parent::current();
        $empleado = $this->denormalizer->denormalize($data, Empleado::class);
        $empleado->setNovasoftDb($this->napiDb);
        return $empleado;
    }
}