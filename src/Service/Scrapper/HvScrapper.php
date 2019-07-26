<?php


namespace App\Service\Scrapper;


use App\Entity\Hv;
use App\Service\Configuracion\Configuracion;
use App\Service\Scrapper\Response\ProcessResponse;
use App\Service\Scrapper\Response\ScrapperResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HvScrapper
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var ScrapperClient
     */
    private $scrapperClient;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SerializerInterface $serializer, ObjectNormalizer $normalizer,
                                ScrapperClient $scrapperClient, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
        $this->scrapperClient = $scrapperClient;
        $this->em = $em;
    }

    public function insert(Hv $hv)
    {
        try {
            $hvNormalized = $this->normailizeHv($hv);
            /** @var ProcessResponse $response */
            $response = $this->scrapperClient->post('/hv/create', $hvNormalized);

            $scrapperProcess = $response->getEntity($hv->getUsuario());
            $this->em->persist($scrapperProcess);
            $this->em->flush();

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function hvUpdate(Hv $hv)
    {
//        $hvSerialized = $this->serializer->serialize($hv, 'json', [
//            'groups' => 'scrapper'
//        ]);
        $hvNormalized = $this->normailizeHv($hv);
        dump($hvNormalized);



        $response = $this->httpClient->request('POST', $this->configuracion->getScrapper()->url . '/hv/create', [
            'json' => $hvNormalized
        ]);


        /*try {
            $decodedPayload = $response->toArray();
            dump($decodedPayload);
//            $hv2 = json_decode($decodedPayload['hv2']);
//            dump($hv2);
        } catch (ClientException | ServerException $e) {
            dump($response->getContent(false));
        }*/
    }

    private function normailizeHv(Hv $hv)
    {
        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(('Y-m-d')) : '';
        };

        $hvNormalized = $this->normalizer->normalize($hv, null, [
            AbstractNormalizer::CALLBACKS => [
                'nacimiento' => $dateCallback,
            ],
            'groups' => 'scrapper'
        ]);

        $hvNormalized += $hvNormalized['usuario'];
        unset($hvNormalized['usuario']);

        //ubicaciones
        foreach($hvNormalized as $prop => $value) {
            if(is_array($value) && count($value) === 1) {
                $key = array_key_first($value);
                $hvNormalized[$prop . ucfirst($key)] = $value[$key];
                unset($hvNormalized[$prop]);
            }
        }

        return $hvNormalized;
    }
}