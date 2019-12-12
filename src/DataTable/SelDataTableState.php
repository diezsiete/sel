<?php


namespace App\DataTable;


use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;

class SelDataTableState extends DataTableState
{
    /**
     * @var bool
     */
    private $isFormPreSubmit;

    public static function fromDefaults(DataTable $dataTable)
    {
        $state = new static($dataTable);
        $state->setStart((int) $dataTable->getOption('start'));
        $state->setLength((int) $dataTable->getOption('pageLength'));

        foreach ($dataTable->getOption('order') as $order) {
            $state->addOrderBy($dataTable->getColumn($order[0]), $order[1]);
        }

        return $state;
    }

    public function setIsFormPreSubmit($isFormPreSubmit = true)
    {
        $this->isFormPreSubmit = $isFormPreSubmit;
        return $this;
    }

    public function isFormPreSubmit()
    {
        return $this->isFormPreSubmit;
    }
}