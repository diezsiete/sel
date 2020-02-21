<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Message\Novasoft\Api\UpdateHvInNovasoft;
use App\Repository\Hv\HvRepository;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateHvInNovasoftHandler implements MessageHandlerInterface
{
    use MessageWithScraperSolicitudHandler;

    /**
     * @var HvRepository
     */
    private $hvRepo;
    /**
     * @var HvClient
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(HvRepository $hvRepo, HvClient $client, EntityManagerInterface $em)
    {
        $this->hvRepo = $hvRepo;
        $this->client = $client;
        $this->em = $em;
    }

    public function __invoke(UpdateHvInNovasoft $message)
    {
        $this->em->clear();
        $hv = $this->hvRepo->find($message->getHvId());
        if($hv) {
            //$this->client->put($hv);
            $this->handleRequest($this->em, $message, function () use($hv) {
                return $this->client->put($hv);
            });
        }
    }
}