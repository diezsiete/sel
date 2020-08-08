<?php


namespace App\Event\EventSubscriber\Api;


use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Hv\Adjunto;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

abstract class ResolveContentUrlAttributeSubscriber implements EventSubscriberInterface
{
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || !\is_a($attributes['resource_class'], $this->getResourceClass(), true)) {
            return;
        }

        $resources = $controllerResult;

        if (!is_iterable($resources)) {
            $resources = [$resources];
        }

        foreach ($resources as $resource) {

            if (!is_a($resource, $this->getResourceClass())) {
                continue;
            }

            $resource->contentUrl = $this->storage->resolveUri($resource, 'file');
        }
    }

    abstract protected function getResourceClass(): string;
}