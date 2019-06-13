<?php


namespace App\Command\Migration;


use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class MigrationCommand extends Command
{
    const CONNECTION_DEFAULT = "default";
    const CONNECTION_SE = "se";
    const CONNECTION_SE_VACANTES = "se_vacantes";

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $defaultManager = null;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository = null;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var string
     */
    protected $limit;


    protected $batchSize = 20;

    /**
     * @var \Doctrine\DBAL\Driver\Statement
     */
    protected $currentStmt = null;

    /**
     * @var string
     */
    protected $offset;
    /**
     * @var Connection|object
     */
    private $seConnection;
    /**
     * @var int
     */
    private $batchCount = 0;
    /**
     * @var ProgressBar|null
     */
    protected $progressBar = null;
    /**
     * @var OutputInterface
     */
    private $output;

    protected $connections = [];

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct();
        $this->doctrine = $managerRegistry;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->offset = $input->getArgument('offset');
        $this->limit = $input->getArgument('limit');
        $this->output = $output;

        if($input->getOption('down')) {
            $this->setCode([$this, 'down']);
        }

        $return = parent::run($input, $output);


        if($this->progressBar) {
            $this->progressBar->finish();
            $this->io->writeln('');
        }

        return $return;
    }

    protected function down()
    {
        $this->io->warning("Metodo down vacio");
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument('offset', InputArgument::OPTIONAL, 'offset')
            ->addArgument('limit', InputArgument::OPTIONAL, 'limit')
            ->addOption('down', null, InputOption::VALUE_NONE);
        ;
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
     * @param $count
     * @param string $format
     * @return ProgressBar
     */
    protected function initProgressBar($count, $format = 'debug')
    {
        $this->progressBar = new ProgressBar($this->output, $count);
        $this->progressBar->setFormat($format);
        return $this->progressBar;
    }


    /**
     * @param Connection $conn
     * @param string $sql
     * @return int
     */
    protected function countSql($sql, $connectionName = self::CONNECTION_SE)
    {
        $conn = $this->getConnection($connectionName);
        if (preg_match('/LIMIT *(\d+)(?:, *(\d+)|)/', $sql, $matches)) {
            if(count($matches) === 3) {
                $count = (int)$matches[2];
            } else {
                $count = (int)$matches[1];
            }
        } else {
            $sqlCount = preg_replace('/(SELECT)(.*)(FROM.*)/', '$1 COUNT(*) $3', $sql);
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

    protected function flushAndClear()
    {
        $em = $this->getDefaultManager();
        $em->flush();
        $em->clear();
    }

    /**
     * @param $idOld
     * @return Usuario|object|null
     */
    protected function getUsuarioByIdOld($idOld, $errorMessage = null)
    {
        if(!$this->usuarioRepository) {
            $this->usuarioRepository = $this->getDefaultManager()->getRepository(Usuario::class);
        }
        $usuario = $this->usuarioRepository->findOneBy(['idOld' => $idOld]);
        if(!$usuario) {
            if(!$errorMessage) {
                $errorMessage = "Usuario con idOld '%id%' no encontrado";
            }
            $errorMessage = str_replace('%id%', $idOld, $errorMessage);
            $this->io->error($errorMessage);
        }
        return $usuario;
    }

    protected function addLimitToSql($sql)
    {
        if($this->offset !== null) {
            $sql .= " LIMIT $this->offset";
        }
        if($this->limit) {
            $sql .= ", $this->limit";
        }
        return $sql;
    }

    protected function selPersist($object)
    {
        $this->getDefaultManager()->persist($object);
        if (($this->batchCount % $this->batchSize) === 0) {
            $this->flushAndClear();
        }
        $this->batchCount++;
        if($this->progressBar) {
            $this->progressBar->advance();
        }
    }

    /**
     * @param $connectionName
     * @return Connection|object
     */
    protected function getConnection($connectionName = self::CONNECTION_SE)
    {
        if(!isset($this->connections[$connectionName]) || $this->connections[$connectionName] === null) {
            $this->connections[$connectionName] = $this->doctrine->getConnection($connectionName);
        }
        return $this->connections[$connectionName];
    }

    protected function fetch($sql, $connectionName = self::CONNECTION_SE)
    {
        if(!$this->currentStmt) {
            $this->currentStmt = $this->getConnection($connectionName)->query($sql);
        }

        $row = $this->currentStmt->fetch();
        if(!$row) {
            $this->flushAndClear();
            $this->currentStmt = null;
            $this->batchCount = 0;
        }
        return $row;
    }

    protected function truncateTable(string $className, $disableForeignKeyChecks = true)
    {
        $em = $this->getDefaultManager();
        $cmd = $em->getClassMetadata($className);
        $connection = $this->getConnection(self::CONNECTION_DEFAULT);
        $connection->beginTransaction();
        try {
            if($disableForeignKeyChecks) {
                $connection->query('SET FOREIGN_KEY_CHECKS=0');
            }
            $connection->query('TRUNCATE TABLE '.$cmd->getTableName());
            if($disableForeignKeyChecks) {
                $connection->query('SET FOREIGN_KEY_CHECKS=1');
            }
            $connection->commit();
            $em->flush();
        } catch (\Exception $e) {
            try {
                $this->io->writeln('Can\'t truncate table ' . $cmd->getTableName() . '. Reason: ' . $e->getMessage(), TRUE);
                $connection->rollback();
                return false;
            } catch (ConnectionException $connectionException) {
                $this->io->writeln(STDERR, print_r('Can\'t rollback truncating table ' . $cmd->getTableName() . '. Reason: ' . $connectionException->getMessage(), TRUE));
                return false;
            }
        }
        return true;
    }

    protected function deleteTable(string $className)
    {
        $em = $this->getDefaultManager();
        $cmd = $em->getClassMetadata($className);
        $connection = $this->getConnection(self::CONNECTION_DEFAULT);
        $connection->beginTransaction();

        $connection->query('DELETE FROM '.$cmd->getTableName());
        $connection->commit();
        $em->flush();
    }
}
