<?php


namespace App\Command\Migration;


use App\Entity\Empleado;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use App\Service\Scraper\ConvenioScraper;
use App\Service\Scraper\Exception\ScraperNotFoundException;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationEmpleadoConvenio extends MigrationCommand
{
    public static $defaultName = "sel:migration:empleado-convenio";
    /**
     * @var EmpleadoRepository
     */
    private $empleadoRepository;
    /**
     * @var ConvenioScraper
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

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry,
                                EmpleadoRepository $empleadoRepository, ConvenioRepository $convenioRepository, ConvenioScraper $scraper,
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
        $empleados = $this->empleadoRepository->findWithoutConvenio();

        $this->initProgressBar(count($empleados));

        foreach($empleados as $empleado) {
            $ident = $empleado->getUsuario()->getIdentificacion();
            try {
                $response = $this->scraper->findConvenioForEmpleado($ident);
                $convenioCodigo = $response->getMessage();

                if($test) {
                    $this->io->writeln("'$ident' ---> '$convenioCodigo'");
                    if($output->isVeryVerbose()) {
                        dump($response);
                    }
                }
                if($convenio = $this->convenioRepository->find($convenioCodigo)) {
                    if(!$test) {
                        $empleado->setConvenio($convenio);
                        $this->em->flush();
                    }
                } else {
                    $this->io->warning("for ident '$ident', convenio '$convenioCodigo' not found in database");
                }
            } catch (ScraperNotFoundException $e) {
                $this->io->warning("convenio not found for '$ident'");
            } catch (Exception $e) {
                $this->io->error($e->getMessage());
            }
            $this->progressBar->advance();
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $query = $this->em->createQuery("UPDATE " . Empleado::class . " e SET e.convenio = NULL");
        $query->getResult();
    }
}