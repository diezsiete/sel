<?php

namespace App\Messenger\Transport\Scraper;

use Doctrine\DBAL\Connection as DBALConnection;
use Doctrine\DBAL\Schema\Synchronizer\SchemaSynchronizer;
use Throwable;

class ConnectionHv extends \Symfony\Component\Messenger\Transport\Doctrine\Connection
{
    private $driverConnection;
    private $tableName;

    private const DEFAULT_OPTIONS = [
        'auto_setup' => false,
    ];

    public static function buildConfiguration($dsn, array $options = [])
    {
        $configuration = $options + self::DEFAULT_OPTIONS;
        $configuration['auto_setup'] = filter_var($configuration['auto_setup'], FILTER_VALIDATE_BOOLEAN);
        return parent::buildConfiguration($dsn, $configuration);
    }


    public function __construct(array $configuration, DBALConnection $driverConnection, SchemaSynchronizer $schemaSynchronizer = null)
    {
        parent::__construct($configuration, $driverConnection, $schemaSynchronizer);
        $this->driverConnection = $driverConnection;
        $this->tableName = $this->getConfiguration()['table_name'];
    }

    public function send(string $body, array $headers, int $delay = 0): string
    {
        if(isset($headers['hvId'])) {
            $hvId = $headers['hvId'];
            unset($headers['hvId']);
            $id = parent::send($body, $headers, $delay);
            $this->updateHvId($id, $hvId);
            return $id;
        }
        return parent::send($body, $headers, $delay);
    }

    public function hvIdHasFailed($hvId)
    {
        $query = $this->driverConnection->createQueryBuilder()
            ->select('COUNT(m.id)')
            ->from($this->tableName, 'm')
            ->where('m.hv_id = ?')
            ->andWhere('m.queue_name = ?')
            ->setMaxResults(1);
        return !!(int)$this->driverConnection->executeQuery($query->getSQL(), [$hvId, 'failed'])->fetchColumn();
    }

    private function updateHvId($commandId, $hvId)
    {
        $this->driverConnection->beginTransaction();
        try {
            $queryBuilder = $this->driverConnection->createQueryBuilder()
                ->update($this->tableName)
                ->set('hv_id', '?')
                ->where('id = ?');

            $this->driverConnection->executeQuery($queryBuilder->getSQL(), [$hvId, $commandId]);

            $this->driverConnection->commit();
        } catch (Throwable $e) {
            $this->driverConnection->rollBack();
            throw $e;
        }
    }
}