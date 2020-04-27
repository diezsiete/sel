<?php


namespace App\Service\Novasoft\Api\Client;


use App\Service\Novasoft\Api\Helper\Hydra\HydraCollection;
use App\Service\Novasoft\Api\Helper\Hydra\HydraCollectionPage;

/**
 * Class ConvenioClient
 * @package App\Service\Novasoft\Api\Client
 * @deprecated
 */
class ConvenioClient extends NovasoftApiClient
{
    public function getConveniosRaw(?string $db = null)
    {
        $response = $this->db($db)->sendGet('/convenios');
        return new HydraCollection($this, new HydraCollectionPage($response));
    }
}