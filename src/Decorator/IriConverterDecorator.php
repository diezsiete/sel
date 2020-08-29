<?php


namespace App\Decorator;


use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\Hv\FactorRh;

/**
 * Unicamente para que el iri de factorRh + retorne el signo + y no el urlencoded
 * Class IriConverterDecorator
 * @package App\Decorator
 */
class IriConverterDecorator implements IriConverterInterface
{
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    public function __construct(IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    /**
     * @inheritDoc
     */
    public function getItemFromIri(string $iri, array $context = [])
    {
        return $this->iriConverter->getItemFromIri($iri, $context);
    }

    /**
     * @inheritDoc
     */
    public function getIriFromItem($item, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        $iri = $this->iriConverter->getIriFromItem($item, $referenceType);
        if($item instanceof FactorRh) {
            $iri = str_replace('%252B', '+', $iri);
        }
        return $iri;
    }

    /**
     * @inheritDoc
     */
    public function getIriFromResourceClass(string $resourceClass, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->iriConverter->getIriFromResourceClass($resourceClass, $referenceType);
    }

    /**
     * @inheritDoc
     */
    public function getItemIriFromResourceClass(string $resourceClass, array $identifiers, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->iriConverter->getItemIriFromResourceClass($resourceClass, $identifiers, $referenceType);
    }

    /**
     * @inheritDoc
     */
    public function getSubresourceIriFromResourceClass(string $resourceClass, array $identifiers, int $referenceType = UrlGeneratorInterface::ABS_PATH): string
    {
        return $this->iriConverter->getSubresourceIriFromResourceClass($resourceClass, $identifiers, $referenceType);
    }
}