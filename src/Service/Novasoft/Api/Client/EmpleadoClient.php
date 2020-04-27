<?php


namespace App\Service\Novasoft\Api\Client;


use App\Entity\Main\Empleado;
use App\Service\Novasoft\Api\Helper\EmpleadoCollection;
use App\Service\Novasoft\Api\Helper\Hydra\HydraCollection;
use App\Service\Novasoft\Api\Helper\Hydra\HydraCollectionPage;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

/**
 * Class EmpleadoClient
 * @package App\Service\Novasoft\Api\Client
 * @deprecated
 */
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
                $empleado->setNovasoftDb($this->getDb());
            }
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
        return $empleado;
    }

    /**
     * @param string $id
     * @param string|null $db
     * @return array|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getRaw(string $id, ?string $db = null)
    {
        return $this->db($db)->sendGet("/empleado/$id/sel");
    }

    /**
     * @param string|null $codigoConvenio
     * @param string|null $db
     * @return EmpleadoCollection|Empleado[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getEmpleadosByConvenio(?string $codigoConvenio=null, ?string $db = null)
    {
        $url = '/empleados/sel' . ($codigoConvenio ? '?convenio=' . $codigoConvenio : '');
        return new EmpleadoCollection(
            $this,
            new HydraCollectionPage($this->db($db)->sendGet($url)),
            $this->denormalizer,
            $this->getDb()
        );
    }

    /**
     * @param string|null $codigoConvenio
     * @param string|null $db
     * @return HydraCollection
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getEmpleadosRawByConvenio(?string $codigoConvenio = null, ?string $db = null)
    {
        $url = '/empleados/sel' . ($codigoConvenio ? '?convenio=' . $codigoConvenio : '');
        return new HydraCollection($this, new HydraCollectionPage($this->db($db)->sendGet($url)));
    }
}