<?php


namespace App\Service\Scraper;


use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\ScraperProcess;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * Class HvScrapper
 * @package App\Service\Scraper
 * @method null updateHv(Hv $hv)
 *      @see HvScraper::__updateHv
 */
class HvScraper
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var ScraperClient
     */
    private $scraperClient;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(NormalizerInterface $normalizer, LoggerInterface $logger,
                                ScraperClient $scraperClient, EntityManagerInterface $em)
    {
        $this->normalizer = $normalizer;
        $this->scraperClient = $scraperClient;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function __call($name, $arguments)
    {
        try {
            if(method_exists($this, "__$name")) {
                /** @var HvEntity $hvEntity */
                $hvEntity = $arguments[0];
                $response = call_user_func_array([$this, "__$name"], $arguments);

                if (isset($response['id'])) {
                    $process = new ScraperProcess($response['id'], $response['estado'], $response['porcentaje'], $hvEntity->getHv()->getUsuario());
                    $this->em->persist($process);
                    $this->em->flush();
                    $this->logger->info("process " . $response['id'] . " received and stored");
                } else {
                    $this->logger->info($response['message']);
                }
            } else {
                throw new \Exception("method '__$name' doesn't exists");
            }
        } catch (\Exception $e) {
            throw $e;
            // $this->logger->error($e->getMessage());
        }
    }

    public function putHv(Hv $hv)
    {
        $data = $this->normalizer->normalize($hv, null, [
            'groups' => ['scraper-hv']
        ]);
        return $this->scraperClient->put('/novasoft/hv/datos-basicos', $data);
    }

    public function insertChild(HvEntity $hvEntity)
    {
        $data = $this->normalizer->normalize($hvEntity->getHv(), null, [
            'groups' => ['scraper-hv-child'], 'scraper-hv-child' => $hvEntity]);

        return $this->scraperClient->post('/novasoft/hv/child', $data);
    }

    public function updateChild(HvEntity $hvEntity)
    {
        $data = $this->normalizer->normalize($hvEntity->getHv(), null, [
            'groups' => ['scraper-hv-child'], 'scraper-hv-child' => get_class($hvEntity)]);

        return $this->scraperClient->put('/novasoft/hv/child', $data);
    }

    public function deleteChild(Hv $hv, string $childClass)
    {
        $data = $this->normalizer->normalize($hv, null, [
            'groups' => ['scraper-hv-child'], 'scraper-hv-child' => $childClass]);

        return $this->scraperClient->put('/novasoft/hv/child', $data);
    }
    
}
