<?php


namespace App\Service\Scraper;


use App\Entity\Hv;
use App\Entity\HvEntity;
use App\Entity\ScraperProcess;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * Class HvScrapper
 * @package App\Service\Scraper
 * @method null updateHv(Hv $hv)
 *      @see HvScraper::__updateHv
 * @method null put(Hv $hv)
 *      @see HvScraper::__put
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
            /** @var HvEntity $hvEntity */
            $hvEntity = $arguments[0];
            $response = call_user_func([$this, "__$name"], $hvEntity);
            if($response['id']) {
                $process = new ScraperProcess($response['id'], $response['estado'], $response['porcentaje'], $hvEntity->getHv()->getUsuario());
                $this->em->persist($process);
                $this->em->flush();
                $this->logger->info("process ".$response['id']." received and stored");
            } else {
                $this->logger->info($response['message']);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function __put(HvEntity $hv)
    {
        $data = $this->normalizer->normalize($hv, null, [
            'groups' => ['scraper']
        ]);
        $response = $this->scraperClient->put('/hv', $data);
        $process = new ScraperProcess($response['id'], $response['estado'], $response['porcentaje'], $hv->getHv()->getUsuario());
        $this->em->persist($process);
        $this->em->flush();
    }

    public function __updateHv(Hv $hv)
    {
        $data = $this->normalizer->normalize($hv, null, [
            'groups' => ['scraper-hv']
        ]);
        return $this->scraperClient->put('/hv', $data);
    }
    
}
