<?php


namespace App\Service\Novasoft\Api\Client;


use App\Entity\Main\Empleado;
use Throwable;

class EmpleadoClient extends NovasoftApiClient
{

    /**
     * @param string $id
     * @param string $db
     * @return Empleado|null
     */
    public function get(string $id, ?string $db = null): ?Empleado
    {
        $empleado = null;
        try {
            if ($data = $this->db($db)->sendGet("/empleado/$id/sel")) {
                /** @var Empleado $empleado */
                $empleado = $this->denormalizer->denormalize($data, Empleado::class);
                $empleado->setNovasoftDb($db);
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $empleado;
    }
}