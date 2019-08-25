<?php


namespace App\Command\Migration;


use App\Command\Helpers\SelCommandTrait;
use App\Entity\Evaluacion\Diapositiva;
use App\Service\Configuracion\Configuracion;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MigrationEvaluacionTemplateCommand extends MigrationCommand
{
    use SelCommandTrait;

    public static $defaultName = "sel:migration:evaluacion:template";
    /**
     * @var FilesystemInterface
     */
    private $templateSourceFilesystem;

    /**
     * @var FilesystemInterface
     */
    private $templateTargetFilesystem;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher, ManagerRegistry $managerRegistry,
                                FilesystemInterface $migrationEvaluacionTemplateFilesystem,
                                FilesystemInterface $migrationEvaluacionTemplateTargetFilesystem,
                                Configuracion $configuracion)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->templateSourceFilesystem = $migrationEvaluacionTemplateFilesystem;
        $this->templateTargetFilesystem = $migrationEvaluacionTemplateTargetFilesystem;
        $this->configuracion = $configuracion;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription("Migrar plantillas de diapositivas. Ya debio haberse ejecutado sel:migration:evaluacion")
            ->addArgument('evaluacion', InputArgument::OPTIONAL, '
            Nombre de la evaluacion', 'induccion');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diapositivas = $this->em->getRepository(Diapositiva::class)->findBy([], ['id' => 'ASC'], $this->limit, $this->offset);
        $count = count($diapositivas);

        $this->initProgressBar($count);
        $evaluacionSlug = $input->getArgument('evaluacion');

        foreach($diapositivas as $diapositiva) {
            $indice = $diapositiva->getIndice();
            $sourcePath = "/$indice.php";
            if($this->templateSourceFilesystem->has($sourcePath)) {
                $string = $this->templateSourceFilesystem->read($sourcePath);
                $contents = $this->procesarContent($string, $indice, $evaluacionSlug);

                $targetPath = "/{$evaluacionSlug}/{$diapositiva->getSlug()}.html.twig";
                $this->templateTargetFilesystem->write($targetPath, $contents);
            } else {
                $this->io->error("plantilla $sourcePath not found in source");
            }
            $this->progressBar->advance();
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->templateTargetFilesystem->deleteDir($input->getArgument('evaluacion'));
    }

    private function procesarContent($content, $indice, $evaluacionSlug)
    {
        $useSEPattern = "/^<\?php\s*\n+use SE\\\\SE;\n+\?>/m";
        if(preg_match($useSEPattern, $content)) {
            $content = preg_replace($useSEPattern, "", $content);

            $imagePattern = "/<\?=\s?SE::getRaiz\(\)\s?.\s?'\/(?:\w+\/)+(.+)'\s?\?>/m";
            $content = preg_replace($imagePattern, "{{ asset('img/sel/evaluacion/$evaluacionSlug/$1') }}", $content);

            //diapositiva 3 codigo para imprimir mes remplazamos por palabra hoy
            if($indice === 3) {
                $content = preg_replace("/<\?php(?:.|\n)*\?>/m", "Hoy", $content);
            }

            //diapositiva 30 codigo para imprimir mes dejamos pendiente para imprimir en twig
            if($indice === 30) {
                $content = preg_replace('/<\?=(?:.|\n)*\?>/m', "TODO: PENDIENTE REEMPLAZAR MES NOW", $content);
            }
        }

        $webPageUrl = "/https:\/\/www\.pta\.com\.co/m";
        $content = preg_replace($webPageUrl,
            "https://www.{$this->getEmpresa()}.com.co", $content);

        $urlGenerate = "/<\?=\s*SE::generate\(['|\"](.+)['|\"]\)\s*\?>/m";
        if(preg_match($urlGenerate, $content, $groups)) {
            //solo una diapositiva genera ruta a contacto
            $content = preg_replace($urlGenerate, "{{ path('{$this->getEmpresa()}_contacto') }}", $content);
        }

        if($indice === 9) {
            $this->io->warning("No se ha definido que hacer con el link '/pages/blog/requisitos-incapacidades.pdf' en diapositiva $indice");
            $content = str_replace('<?= $url = q(\'/pages/blog/requisitos-incapacidades.pdf\', false); ?>', "#", $content);
        }

        $sourceDiapositivaHasCss = $this->getConnection()->fetchColumn(
            "SELECT css FROM evaluacion_presentacion_diapositiva WHERE indice = $indice");
        if($sourceDiapositivaHasCss) {
            $content = str_replace("<div class=\"cuadrados\"></div>", "", $content);
        }

        $content = str_replace("@pta.com.co", "@{$this->getEmpresa()}.com.co", $content);
        $content = str_replace('PTA SAS', $this->getEmpresa(false) . " SAS", $content);


        $extends = $sourceDiapositivaHasCss ? 'evaluacion/base-titulo.html.twig' : 'evaluacion/base.html.twig';
        return "{% extends '$extends' %}\n\n"."{% block body %}\n".$content."\n{% endblock %}";
    }

    private function getEmpresa($lowerCased = true)
    {
        return $this->configuracion->getEmpresa($lowerCased);
    }


}