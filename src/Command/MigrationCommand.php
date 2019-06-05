<?php


namespace App\Command;


use App\Entity\Usuario;
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
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var string
     */
    protected $limit;

    /**
     * @var string
     */
    protected $offset;

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
        return parent::run($input, $output);
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
        $usuarioRepository = $this->getDefaultManager()->getRepository(Usuario::class);
        $usuario = $usuarioRepository->findOneBy(['idOld' => $idOld]);
        if(!$usuario) {
            $this->io->error("Usuario con idOld '" . $idOld. "' no encontrado");
        }
        return $usuario;
    }

    protected function addLimitToSql($sql)
    {
        if($this->offset) {
            $sql .= " LIMIT $this->offset";
        }
        if($this->limit) {
            $sql .= ", $this->limit";
        }
        return $sql;
    }
}