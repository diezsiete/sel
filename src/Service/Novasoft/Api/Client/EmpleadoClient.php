<?php


namespace App\Service\Novasoft\Api\Client;


use App\Entity\Main\Empleado;
use Throwable;

class EmpleadoClient extends NovasoftApiClient
{

    /**
     * @param string $id
     * @return Empleado|null
     */
    public function get(string $id): ?Empleado
    {
        $empleado = null;
        try {
            if ($data = $this->sendGet("/empleado/$id/sel")) {
                /** @var Empleado $empleado */
                $empleado = $this->denormalizer->denormalize($data, Empleado::class);
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $empleado;
    }
}