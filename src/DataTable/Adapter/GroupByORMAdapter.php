<?php

namespace App\DataTable\Adapter;

use App\DataTable\Column\CheckboxColumn;
use App\DataTable\SelDataTableState;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\AdapterQuery;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\AbstractColumn;

class GroupByORMAdapter extends ORMAdapter
{
    protected $hydrationMode;

    public function configure(array $options)
    {
        parent::configure($options);

        $this->hydrationMode = isset($options['hydrate']) ? $options['hydrate'] : Query::HYDRATE_OBJECT;
    }

    protected function prepareQuery(AdapterQuery $query)
    {
        parent::prepareQuery($query);
        $query->setIdentifierPropertyPath(null);
    }

    /**
     * @param AdapterQuery $query
     * @return \Traversable
     */
    protected function getResults(AdapterQuery $query): \Traversable
    {
        /** @var QueryBuilder $builder */
        $builder = $query->get('qb');
        $state = $query->getState();

        if(!($state instanceof SelDataTableState) || !$state->isFormPreSubmit()) {
            // Apply definitive view state for current 'page' of the table
            foreach ($state->getOrderBy() as list($column, $direction)) {
                /** @var AbstractColumn $column */
                if ($column->isOrderable()) {
                    $builder->addOrderBy($column->getOrderField(), $direction);
                }
            }
            if ($state->getLength() > 0) {
                $builder
                    ->setFirstResult($state->getStart())
                    ->setMaxResults($state->getLength());
            }
        }

        /**
         * Use foreach instead of iterate to prevent group by from crashing
         */
        foreach ($builder->getQuery()->getResult($this->hydrationMode) as $result) {
            /**
             * Return everything instead of first element
             */
            yield $result;
        }
    }

    protected function getPropertyMap(AdapterQuery $query): array
    {
        $state = $query->getState();
        if(!($state instanceof SelDataTableState) || !$state->isFormPreSubmit()) {
            return parent::getPropertyMap($query);
        } else {
            $propertyMap = [];
            foreach ($query->getState()->getDataTable()->getColumns() as $column) {
                if($column instanceof CheckboxColumn) {
                    $column->isFormPreSubmit(true);
                    $propertyMap[] = [$column, $column->getPropertyPath() ?? (empty($column->getField()) ? null : $this->mapPropertyPath($query, $column))];
                }
            }
            return $propertyMap;
        }
    }
}