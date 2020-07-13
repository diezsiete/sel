<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryResultItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Helper\DataProvider\DataProviderRequestStack;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class OneByItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    use DataProviderRequestStack;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var iterable
     */
    private $itemExtensions;

    public function __construct(ManagerRegistry $managerRegistry, iterable $itemExtensions)
    {
        $this->managerRegistry = $managerRegistry;
        $this->itemExtensions = $itemExtensions;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $operationName === 'one-by';
    }

    /**
     * @inheritDoc
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if($manager = $this->managerRegistry->getManagerForClass($resourceClass)) {
            $repository = $manager->getRepository($resourceClass);
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $repository->createQueryBuilder('o');
            $queryNameGenerator = new QueryNameGenerator();
            $identifiers = [$this->getRouteParameter('field') => $id];

            $doctrineClassMetadata = $manager->getClassMetadata($resourceClass);

            $this->addWhereForIdentifiers($identifiers, $queryBuilder, $doctrineClassMetadata);

            foreach ($this->itemExtensions as $extension) {
                $extension->applyToItem($queryBuilder, $queryNameGenerator, $resourceClass, $identifiers, $operationName, $context);
                if ($extension instanceof ContextAwareQueryResultItemExtensionInterface && $extension->supportsResult($resourceClass, $operationName, $context)) {
                    return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
                }
            }
            return $queryBuilder->getQuery()->getOneOrNullResult();
        }
        return null;
    }

    /**
     * Add WHERE conditions to the query for one or more identifiers (simple or composite).
     * @param array $identifiers
     * @param QueryBuilder $queryBuilder
     * @param ClassMetadata $classMetadata
     */
    private function addWhereForIdentifiers(array $identifiers, QueryBuilder $queryBuilder, ClassMetadata $classMetadata)
    {
        $alias = $queryBuilder->getRootAliases()[0];
        foreach ($identifiers as $identifier => $value) {
            $placeholder = ':id_'.$identifier;
            $expression = $queryBuilder->expr()->eq(
                "{$alias}.{$identifier}",
                $placeholder
            );

            $queryBuilder->andWhere($expression);

            $queryBuilder->setParameter($placeholder, $value, $classMetadata->getTypeOfField($identifier));
        }
    }
}