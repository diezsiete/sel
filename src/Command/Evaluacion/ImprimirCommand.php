<?php /** @noinspection PhpParamsInspection */


namespace App\Command\Evaluacion;


use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Main\Usuario;
use App\Repository\Evaluacion\EvaluacionRepository;
use App\Repository\Evaluacion\ProgresoRepository;
use App\Service\Evaluacion\Navegador;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;

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
    /**
     * @var Environment
     */
    private $twig;
    private $kernelProjectDir;

    public function __construct(Navegador $navegador, EvaluacionRepository $evaluacionRepository, Environment $twig,
                                ProgresoRepository $progresoRepository, EntityManagerInterface $em, $kernelProjectDir)
    {
        parent::__construct();
        $this->navegador = $navegador;
        $this->evaluacionRepository = $evaluacionRepository;
        $this->progresoRepository = $progresoRepository;
        $this->em = $em;
        $this->twig = $twig;
        $this->kernelProjectDir = $kernelProjectDir;
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

        $i = 0;

        do {
            $currentRoute = $this->navegador->getCurrentRoute();
            $html = $this->getHtml();
            $html = str_replace('href="/', 'href="https://www.servilabor.com.co/', $html);
            $this->generatePdf($html);
            $i++;
        } while ($this->advance() && $i < 10);

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

    protected function getHtml()
    {
        $pregunta = null;
        if ($this->isDiapositiva($this->navegador->getCurrentRoute())) {
            $view = "evaluacion/{$this->navegador->getEvaluacion()->getSlug()}/{$this->navegador->getDiapositiva()->getSlug()}.html.twig";
        } else {
            $pregunta = $this->navegador->getPregunta();
            $template = $pregunta->getWidgetAsKebabCase();
            $view = "evaluacion/widget/$template.html.twig";
        }

        $context = [
            'evaluacion' => $this->navegador->getEvaluacion(),
            'navegador' => $this->navegador,
            'imprimir' => true,
        ];

        if($pregunta) {
            $context['pregunta'] = $pregunta;
        }

        return $this->twig->render($view, $context);
    }

    protected function generatePdf($html)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        $pdfFilePath = $this->kernelProjectDir . "/var/uploads/evaluacion/{$this->navegador->getDiapositiva()->getSlug()}.pdf";

        file_put_contents($pdfFilePath, $output);
    }
}