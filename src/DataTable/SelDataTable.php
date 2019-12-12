<?php


namespace App\DataTable;


use Omines\DataTablesBundle\DataTable;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;

class SelDataTable extends DataTable
{
    /**
     * @var FormBuilderInterface
     */
    private $form;

    public function setForm(FormBuilderInterface $form)
    {
        $this->form = $form;
    }

    public function handleRequest(Request $request): DataTable
    {
        parent::handleRequest($request);

        $this->form->get('datatable')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $state = SelDataTableState::fromDefaults($this)->setIsFormPreSubmit();
            $data = [];
            foreach($this->adapter->getData($state)->getData() as $row) {
                $data[] = $row['datatable'];
            }

            $event->setData(implode(",", $data));
            $state->setIsFormPreSubmit(false);
        });

        return $this;
    }
}