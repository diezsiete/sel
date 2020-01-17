<?php


namespace App\Event\Event\DataTable;


use Omines\DataTablesBundle\DataTableState;
use Symfony\Contracts\EventDispatcher\Event;

class PreGetResultsEvent extends Event
{
    /**
     * @var DataTableState
     */
    private $state;

    public function __construct(DataTableState $state)
    {
        $this->state = $state;
    }

    /**
     * @return DataTableState
     */
    public function getState(): DataTableState
    {
        return $this->state;
    }
}