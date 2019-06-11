<?php


namespace App\Form;


use Craue\FormFlowBundle\Form\FormFlow;

class HvFlow extends FormFlow
{
    protected function loadStepsConfig()
    {
        return [
            [
                'label' => 'Datos basicos',
                'form_type' => HvFormType::class,
            ],
            [
                'label' => 'Estudio',
                'form_type' => EstudioFormType::class,

            ],
            [
                'label' => 'confirmation',
            ],
        ];
    }

}