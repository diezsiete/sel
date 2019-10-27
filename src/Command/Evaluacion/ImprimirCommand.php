<?php /** @noinspection PhpParamsInspection */


namespace App\Command\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Usuario;
use App\Repository\Evaluacion\EvaluacionRepository;
use App\Repository\Evaluacion\ProgresoRepository;
use App\Service\Evaluacion\Navegador;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImprimirCommand extends Command
{
    protected static $defaultName = "sel:evaluacion:imprimir";
    /**
     * @var Navegador
     */
    private $navegador;
    /**
     * @var EvaluacionRepository
     */
    private $evaluacionRepository;
    /**
     * @var ProgresoRepository
     */
    private $progresoRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Navegador $navegador, EvaluacionRepository $evaluacionRepository,
                                ProgresoRepository $progresoRepository, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->navegador = $navegador;
        $this->evaluacionRepository = $evaluacionRepository;
        $this->progresoRepository = $progresoRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this->addArgument('slug', InputArgument::REQUIRED, 'Evaluacion slug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $evaluacion = $this->evaluacionRepository->findOneBy(['slug' => $input->getArgument('slug')]);
        $progreso = $this->progresoRepository->instanceNewProgreso(new Usuario(), $evaluacion);
        $this->navegador
            ->setEvaluacion($evaluacion)
            ->setProgreso($progreso);

        do {
            $currentRoute = $this->navegador->getCurrentRoute();
            dump($currentRoute);
            dump($this->isDiapositiva($currentRoute) ? "DIAPOSITIVA" : "PREGUNTA");
        } while ($this->advance());
    }

    protected function advance()
    {
        if($this->navegador->hasNextRoute()) {
            $match = $this->match($this->navegador->getNextRoute());
            if($this->isDiapositiva($this->navegador->getNextRoute())) {
                $this->navegador->setRouteDiapositiva(
                    $this->em->getRepository(Modulo::class)->findOneBy(['slug' => $match['moduloSlug']]),
                    $this->em->getRepository(Diapositiva::class)->findOneBy(['slug' => $match['diapositivaSlug']])
                );
            } else {
                $this->navegador->setRoutePregunta(
                    $this->em->getRepository(Modulo::class)->findOneBy(['slug' => $match['moduloSlug']]),
                    $this->em->getRepository(Pregunta::class)->find($match['preguntaId'])
                );
            }
            return true;
        }
        return false;
    }

    protected function isDiapositiva($match)
    {
        $match = is_array($match) ? $match : $this->match($match);
        return isset($match['diapositivaSlug']);
    }

    protected function isPregunta($match)
    {
        $match = is_array($match) ? $match : $this->match($match);
        return isset($match['preguntaId']);
    }

    protected function match($route)
    {
        return $this->navegador->getRouter()->match($route);
    }
}