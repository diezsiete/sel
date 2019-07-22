<?php


namespace App\Command\Helpers\ModifyRun;


use App\Command\Helpers\ModifyRun\Event\ModifyRunAfterEvent;
use App\Command\Helpers\ModifyRun\Event\ModifyRunBeforeEvent;
use App\Command\Helpers\ModifyRun\Annotation\ModifyRunAfter;
use App\Command\Helpers\ModifyRun\Annotation\ModifyRunBefore;
use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait ModifyRun
{

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @required
     */
    public function setAnnotationReader(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    protected function modifyRun()
    {
        $methods = get_class_methods($this);
        foreach($methods as $methodName) {
            $reflectionMethod = new ReflectionMethod($this, $methodName);
            $methodAnnotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);
            if($methodAnnotations) {
                foreach($methodAnnotations as $methodAnnotation) {
                    if($methodAnnotation instanceof ModifyRunBefore) {
                        $this->eventDispatcher->addListener(ModifyRunBeforeEvent::class, [$this, $methodName]);
                    }
                    else if($methodAnnotation instanceof ModifyRunAfter) {
                        $this->eventDispatcher->addListener(ModifyRunAfterEvent::class, [$this, $methodName]);
                    }
                }
            }
        }
    }
    
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->modifyRun();

        $this->eventDispatcher->dispatch(new ModifyRunBeforeEvent($input, $output));

        $return = parent::run($input, $output);

        $this->eventDispatcher->dispatch(new ModifyRunAfterEvent($input, $output));

        return $return;
    }
}