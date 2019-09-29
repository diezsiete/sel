<?php


namespace App\Command\Helpers\TraitableCommand;


use App\Command\Helpers\TraitableCommand\Annotation\AfterRun;
use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use App\Command\Helpers\TraitableCommand\Event\AfterRunEvent;
use App\Command\Helpers\TraitableCommand\Event\BeforeRunEvent;
use App\Command\Helpers\TraitableCommand\Event\ConfigureEvent;
use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class TraitableCommand extends Command
{

    protected static $annotationsEvents = [
        AfterRun::class => AfterRunEvent::class,
        BeforeRun::class => BeforeRunEvent::class,
        Configure::class => ConfigureEvent::class
    ];

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;


    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Reader
     */
    private $annotationReader;


    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher)
    {
        $this->annotationReader = $annotationReader;
        $this->eventDispatcher = $eventDispatcher;

        $this->addListeners();

        parent::__construct();
    }

    protected function configure()
    {
        $this->eventDispatcher->dispatch(new ConfigureEvent());

        $this->removeListeners(Configure::class);

        parent::configure();
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->input = $input;
        $this->output = $output;

        $return = parent::run($input, $output);

        $this->eventDispatcher->dispatch(new AfterRunEvent($this, $input, $output));

        return $return;
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->eventDispatcher->dispatch(new BeforeRunEvent($this, $input, $output));
    }

    private function addListeners()
    {
        $methods = get_class_methods($this);
        foreach($methods as $methodName) {
            $reflectionMethod = new ReflectionMethod($this, $methodName);
            $methodAnnotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);
            if($methodAnnotations) {
                foreach($methodAnnotations as $methodAnnotation) {
                    foreach(static::$annotationsEvents as $annotationClass => $eventClass) {
                        if($methodAnnotation instanceof $annotationClass) {
                            $this->eventDispatcher->addListener($eventClass, [$this, $methodName]);
                        }
                    }
                }
            }
        }
    }

    private function removeListeners($annotationClass)
    {
        $methods = get_class_methods($this);
        foreach($methods as $methodName) {
            $reflectionMethod = new ReflectionMethod($this, $methodName);
            if($methodAnnotations = $this->annotationReader->getMethodAnnotations($reflectionMethod)) {
                foreach($methodAnnotations as $methodAnnotation) {
                    if($methodAnnotation instanceof $annotationClass) {
                        $this->eventDispatcher->removeListener(static::$annotationsEvents[$annotationClass], [$this, $methodName]);
                    }
                }
            }
        }
    }
}