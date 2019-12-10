<?php


namespace App\Command\Migration;


use App\Entity\Empleado;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Service\Scraper\NovasoftDataScraper;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationEmpleadoConvenioCommand extends MigrationCommand
{
    public static $defaultName = "sel:migration:empleado-convenio";
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var NovasoftDataScraper
     */
    private $scraper;
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $convenios = [];

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry,
                                EmpleadoRepository $empleadoRepository, ConvenioRepository $convenioRepository, NovasoftDataScraper $scraper,
                                EntityManagerInterface $em)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->empleadoRepository = $empleadoRepository;
        $this->convenioRepository = $convenioRepository;
        $this->scraper = $scraper;
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('test', 't', InputOption::VALUE_NONE,
            'Test. solo muestra informacion. utilizar -vv para ver response de scraper');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = $input->getOption('test');
        $idents = $this->empleadoRepository->findIdentsWithoutConvenio();

        $this->initProgressBar(count($idents));

        $batchSize = 20;
        $i = 0;
        foreach($idents as $ident) {

            try {
                $response = $this->scraper->findConvenioForEmpleado($ident);
                $convenioCodigo = $response->getMessage();

                if($test) {
                    $this->io->writeln("'$ident' ---> '$convenioCodigo'");
                }
                if($convenio = $this->getConvenio($convenioCodigo)) {
                    if(!$test) {
                        $this->empleadoRepository->findByIdentificacion($ident)
                            ->setConvenio($convenio);
                    }
                } else {
                    $this->io->warning("for ident '$ident', convenio '$convenioCodigo' not found in database");
                }
            } catch (ScraperNotFoundException $e) {
                $this->io->warning("convenio not found for '$ident'");
            } catch (Exception $e) {
                $this->io->error($e->getMessage());
            }
            $i++;
            if (($i % $batchSize) === 0 && !$test) {
                $this->em->flush();
                $this->em->clear();
                $this->convenios = [];
            }
            $this->progressBar->advance();
        }
        if(!$test) {
            $this->em->flush();
            $this->em->clear();
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $query = $this->em->createQuery("UPDATE " . Empleado::class . " e SET e.convenio = NULL");
        $query->getResult();
    }

    protected function getConvenio($convenioCodigo)
    {
        if(!isset($this->convenios[$convenioCodigo])) {
            $this->convenios[$convenioCodigo] = $this->convenioRepository->find($convenioCodigo);
        }
        return $this->convenios[$convenioCodigo];
    }
}