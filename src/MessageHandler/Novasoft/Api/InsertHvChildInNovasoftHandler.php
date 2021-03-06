<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Entity\Hv\HvEntity;
use App\Entity\Hv\Referencia;
use App\Message\Novasoft\Api\InsertHvChildInNovasoft;
use App\Service\Novasoft\Api\Client\HvClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class InsertHvChildInNovasoftHandler implements MessageHandlerInterface
{
    use MessageWithScraperSolicitudHandler;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var HvClient
     */
    private $client;

    public function __construct(EntityManagerInterface $em, HvClient $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function __invoke(InsertHvChildInNovasoft $message)
    {
        $this->em->clear();
        /** @var HvEntity $hvEntity */
        $hvEntity = $this->em->getRepository($message->getChildClass())->find($message->getChildId());
        if ($hvEntity) {
//            // referencia primary key es compuesta con autogenerated que doctrine no soporta. toca manejar asi
//            if($message->getChildClass() === Referencia::class) {
//                $this->client->deleteReferencias($hvEntity);
//                $this->client->saveReferencias($hvEntity);
//            } else {
//                $this->client->postChild($hvEntity);
//            }
            $this->handleRequest($this->em, $message, function (InsertHvChildInNovasoft $message) use ($hvEntity) {
                // referencia primary key es compuesta con autogenerated que doctrine no soporta. toca manejar asi
                if($message->getChildClass() === Referencia::class) {
                    $this->client->deleteReferencias($hvEntity);
                    return $this->client->saveReferencias($hvEntity);
                }
                return $this->client->postChild($hvEntity);
            });
        }

    }
}