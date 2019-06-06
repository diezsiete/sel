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
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class MigrationCommand extends Command
{
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

    protected $seStmt = null;

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
        $return = parent::run($input, $output);

        if($this->progressBar) {
            $this->progressBar->finish();
            $this->io->writeln('');
        }

        return $return;
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument('offset', InputArgument::OPTIONAL, 'offset')
            ->addArgument('limit', InputArgument::OPTIONAL, 'limit')
        ;
    }

    /**
     * @return Connection|object
     */
    protected function getSeConnection()
    {
        if(!$this->seConnection) {
            $this->seConnection = $this->doctrine->getConnection('se');
        }
        return $this->seConnection;
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
    protected function countSql($sql, $conn = null)
    {
        if(!$conn) {
            $conn = $this->getSeConnection();
        }
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

    /**
     * @param $idOld
     * @return Usuario|object|null
     */
    protected function getUsuarioByIdOld($idOld)
    {
        if(!$this->usuarioRepository) {
            $this->usuarioRepository = $this->getDefaultManager()->getRepository(Usuario::class);
        }
        $usuario = $this->usuarioRepository->findOneBy(['idOld' => $idOld]);
        if(!$usuario) {
            $this->io->error("Usuario con idOld '" . $idOld. "' no encontrado");
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

    protected function seFetch($sql)
    {
        if(!$this->seStmt) {
            $this->seStmt = $this->getSeConnection()->query($sql);
        }
        $row = $this->seStmt->fetch();
        if(!$row) {
            $em = $this->getDefaultManager();
            $em->flush();
            $em->clear();
            $this->seStmt = null;
            $this->batchCount = 0;
        }
        return $row;
    }

    protected function selPersist($object)
    {
        $em = $this->getDefaultManager();
        $em->persist($object);
        if (($this->batchCount % $this->batchSize) === 0) {
            $em->flush(); // Executes all updates.
            $em->clear(); // Detaches all objects from Doctrine!
        }
        $this->batchCount++;
        if($this->progressBar) {
            $this->progressBar->advance();
        }
    }
}
