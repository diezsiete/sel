<?php


namespace App\Command;


use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class MigrationCommand extends Command
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $defaultManager = null;


    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct();
        $this->doctrine = $managerRegistry;
    }

    /**
     * @return Connection|object
     */
    protected function getSeConnection()
    {
        return $this->doctrine->getConnection('se');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getDefaultManager()
    {
        if(!$this->defaultManager) {
            $this->defaultManager = $this->doctrine->getManager('default');
        }
        return $this->defaultManager;
    }

    /**
     * @param $output
     * @param $count
     * @param string $format
     * @return ProgressBar
     */
    protected function getProgressBar($output, $count, $format = 'debug')
    {
        $progressBar = new ProgressBar($output, $count);
        $progressBar->setFormat($format);
        return $progressBar;
    }


    /**
     * @param Connection $conn
     * @param string $sql
     * @return int
     */
    protected function countSql($conn, $sql)
    {
        if (preg_match('/LIMIT *(\d+)(?:, *(\d+)|)/', $sql, $matches)) {
            if(count($matches) === 3) {
                $count = (int)$matches[2] - (int)$matches[1];
            } else {
                $count = (int)$matches[1];
            }
        } else {
            $sqlCount = str_replace('*', "COUNT(*)", $sql);
            $count = (int) $conn->fetchColumn($sqlCount);
        }
        return $count;
    }

    protected function persistAndFlush($object)
    {
        $em = $this->getDefaultManager();
        $em->persist($object);
        $em->flush();
    }
}