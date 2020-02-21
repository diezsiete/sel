<?php


namespace App\MessageHandler\Novasoft\Api;


use App\Entity\Scraper\Solicitud;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

trait MessageWithScraperSolicitudHandler
{
    public function handleRequest(EntityManagerInterface $em, $message, $callable)
    {
        $solicitud = $em->getRepository(Solicitud::class)->find($message->getSolicitudId());
        try {
            $response = $callable($message);
            $solicitud->addLog(is_array($response) ? json_encode($response) : $response);
        }catch (Exception $e) {
            $solicitud->addLog($e->getMessage());
            throw $e;
        }
        finally {
            $em->flush();
        }
    }


}