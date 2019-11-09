<?php


namespace App\Command\Migration;


use App\Command\Helpers\TraitableCommand\TraitableCommand;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class MigrationCommand extends TraitableCommand
{
    const CONNECTION_DEFAULT = "default";
    const CONNECTION_SE = "se";
    const CONNECTION_SE_ASPIRANTE = "se_aspirante";
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
    protected $usuarioRepository = null;

    protected $repositories = [];

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var int
     */
    protected $limit = null;
    /**
     * @var int
     */
    protected $offset = null;

    protected $batchSize = 20;

    /**
     * @var Statement[]
     */
    protected $currentStmt = [];

    protected $currentStmtQueries = [];

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
    protected $output;
    /**
     * @var InputInterface
     */
    protected $input;

    protected $connections = [];

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->doctrine = $managerRegistry;
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->output = $output;
        $this->input = $input;

        $down = false;

        try {
            $this->offset = $input->getOption('offset') !== null ? (int)$input->getOption('offset') : null;
            $this->limit = $input->getOption('limit') !== null ? (int)$input->getOption('limit') : null;
            $down = $input->getOption('down');
        }catch (\InvalidArgumentException $e) {

        }

        if($down) {
            $this->setCode([$this, 'down']);
        }

        $return = parent::run($input, $output);


        if($this->progressBar) {
            $this->progressBar->finish();
            $this->io->writeln('');
        }

        return $return;
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Autoliquidacion::class);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addOption('offset', 'o',InputOption::VALUE_OPTIONAL, 'offset')
            ->addOption('limit','l', InputOption::VALUE_OPTIONAL, 'limit')
            ->addOption('down', 'd', InputOption::VALUE_NONE,
                'Truncates all tables targets');
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
     * @param $sql
     * @param string $connectionName
     * @return int
     * @throws DBALException
     */
    protected function countSql($sql, $connectionName = self::CONNECTION_SE)
    {
        if(is_array($sql)) {
            $count = 0;
            foreach($sql as $query) {
                $count += $this->countSql($query, $connectionName);
            }
            return $count;
        } else {
            $conn = $this->getConnection($connectionName);
            if (preg_match('/LIMIT *(\d+)(?:, *(\d+)|)/', $sql, $matches)) {
                if (count($matches) === 3) {
                    $count = (int)$matches[2];
                } else {
                    $count = (int)$matches[1];
                }
            } else {
                $noNlSql = preg_replace("/\r|\n/", "", $sql);
                $sqlCount = preg_replace('/(SELECT)(.*)(FROM.*)/', '$1 COUNT(*) $3', $noNlSql);
                $count = (int)$conn->fetchColumn($sqlCount);
            }
            return $count;
        }
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
     * @param null|string|false $errorMessage
     * @return Usuario|null
     */
    protected function getUsuarioByIdOld($idOld, $errorMessage = null)
    {
        if(!$this->usuarioRepository) {
            $this->usuarioRepository = $this->getDefaultManager()->getRepository(Usuario::class);
        }
        $usuario = $this->usuarioRepository->findOneBy(['idOld' => $idOld]);
        if(!$usuario) {
            if($errorMessage !== false) {
                if (!$errorMessage) {
                    $errorMessage = "Usuario con idOld '%id%' no encontrado";
                }
                $errorMessage = str_replace('%id%', $idOld, $errorMessage);
                $this->io->error($errorMessage);
            }
        }
        return $usuario;
    }

    protected function addLimitToSql($sql)
    {
        if($this->offset !== null) {
            $sql .= " LIMIT $this->offset";
        }
        if($this->limit) {
            $sql .= $this->offset ? ", $this->limit" : " LIMIT $this->limit";
        }
        return $sql;
    }

    protected function selPersist($object, $progressBarAdvance = true)
    {
        $this->getDefaultManager()->persist($object);
        if (($this->batchCount % $this->batchSize) === 0) {
            $this->flushAndClear();
        }
        $this->batchCount++;
        if($this->progressBar && $progressBarAdvance) {
            $this->progressBar->advance();
        }
    }

    /**
     * @param $connectionName
     * @return Connection
     */
    protected function getConnection($connectionName = self::CONNECTION_SE)
    {
        if(!isset($this->connections[$connectionName]) || $this->connections[$connectionName] === null) {
            /** @var Connection $connection */
            $connection = $this->doctrine->getConnection($connectionName);
            $this->connections[$connectionName] = $connection;
        }
        return $this->connections[$connectionName];
    }

    protected function fetch($sql, $connectionName = self::CONNECTION_SE)
    {
        $statementIndex = array_search($sql, $this->currentStmtQueries);
        if($statementIndex === false) {
            $this->currentStmtQueries[] = $sql;
            $statementIndex = count($this->currentStmtQueries) - 1;
        }


        if(!isset($this->currentStmt[$statementIndex])) {
            $this->currentStmt[$statementIndex] = $this->getConnection($connectionName)->query($sql);
        }

        $row = $this->currentStmt[$statementIndex]->fetch();
        if(!$row) {
            $this->flushAndClear();
            unset($this->currentStmt[$statementIndex]);
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

    protected function getRepository($class)
    {
        if(!isset($this->repositories[$class])) {
            $this->repositories[$class] = $this->getDefaultManager()->getRepository($class);
        }
        return $this->repositories[$class];
    }
}
