<?php


namespace App\Messenger\Transport\Scraper;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class ScraperTransportFactory implements TransportFactoryInterface
{

    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        unset($options['transport_name']);
        $dsn = str_replace('scraper', 'doctrine', $dsn);
        $configuration = ConnectionHv::buildConfiguration($dsn, $options);

        try {
            $driverConnection = $this->registry->getConnection($configuration['connection']);
        } catch (\InvalidArgumentException $e) {
            throw new TransportException(sprintf('Could not find Doctrine connection from Messenger DSN "%s".', $dsn), 0, $e);
        }

        $connection = new ConnectionHv($configuration, $driverConnection);

        return new ScraperHvTransport($connection, $serializer);
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'scraper://');
    }
}