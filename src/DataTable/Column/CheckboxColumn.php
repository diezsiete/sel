<?php


namespace App\DataTable\Column;


use Omines\DataTablesBundle\Column\AbstractColumn;

class CheckboxColumn extends AbstractColumn
{

    /**
     * @var bool
     */
    private $isFormPreSubmit;

    public function isFormPreSubmit($isPreSubmit = true)
    {
        $this->isFormPreSubmit = $isPreSubmit;
    }
    /**
     * The normalize function is responsible for converting parsed and processed data to a datatables-appropriate type.
     *
     * @param mixed $value The single value of the column
     * @return mixed
     */
    public function normalize($value)
    {
        return $value;
    }

    protected function render($value, $context)
    {
        return $this->isFormPreSubmit
            ? $value
            : "<input type='checkbox' name='datatable[]' value='$value' checked />";
    }
}