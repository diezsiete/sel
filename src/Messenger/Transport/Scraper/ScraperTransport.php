<?php


namespace App\Messenger\Transport\Scraper;


use Symfony\Component\Messenger\Transport\Doctrine\DoctrineTransport;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ScraperTransport extends DoctrineTransport
{
    private $connection;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        parent::__construct($connection, $serializer);
        $this->connection = $connection;
    }

    /**
     * @param $hvId
     * @return bool
     */
    public function hvIdHasFailed($hvId)
    {
        return $this->connection->hvIdHasFailed($hvId);
    }
}