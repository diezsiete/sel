<?php


namespace App\Command\NovasoftImport;


use App\Command\Helpers\ConsoleProgressBar;
use App\Entity\Hv\EstudioInstituto;
use App\Repository\Hv\EstudioInstitutoRepository;
use App\Service\Scraper\NovasoftDataScraper;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NiInstitutoCommand extends NiCommand
{
    use ConsoleProgressBar;

    protected static $defaultName = "sel:ni:instituto";
    /**
     * @var NovasoftDataScraper
     */
    private $scraper;
    /**
     * @var \App\Repository\Hv\EstudioInstitutoRepository
     */
    private $institutoRepository;

    private $institutos = null;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                NovasoftDataScraper $scraper, EstudioInstitutoRepository $institutoRepository, EntityManagerInterface $em)
    {
        parent::__construct($annotationReader, $eventDispatcher);
        $this->scraper = $scraper;
        $this->institutoRepository = $institutoRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        parent::configure();
        $this->addOption('test', 't', InputOption::VALUE_NONE, 'Muestra resultado pero no lo persiste');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = $input->getOption('test');
        $response = $this->scrapeInstitutos();

        foreach($response as $institutoData) {
            if($test) {
                $this->io->writeln(implode(",", $institutoData));
            }
            $instituto = $this->institutoRepository->find($institutoData[0]);
            if($instituto) {
                $instituto->setNombre($institutoData[1]);
            } else {
                $instituto = (new EstudioInstituto())
                    ->setId($institutoData[0])
                    ->setNombre($institutoData[1]);
                $this->persist($instituto, $test);
            }
            $this->flush($test);
            $this->progressBarAdvance();
        }
    }

    private function persist($instituto, $test)
    {
        if(!$test) {
            $this->em->persist($instituto);
        }
    }

    private function flush($test)
    {
        if(!$test) {
            $this->em->flush();
        }
    }

    protected function scrapeInstitutos()
    {
        if($this->institutos === null) {
            $this->io->writeln("scrapping institutos");
            $this->institutos = $this->scraper->getInstitutos()->getMessage();
        }
        return $this->institutos;
    }


    protected function progressBarCount(InputInterface $input, OutputInterface $output): ?int
    {
        return count($this->scrapeInstitutos());
    }
}