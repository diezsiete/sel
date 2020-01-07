<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Entity\Hv\Vivienda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ViviendaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('direccion', null, [
                'label' => 'Dirección'
            ])
            ->add('estrato')
            ->add('pais', null, [
                'placeholder' => 'Seleccione...'
            ])
            ->add('dpto', null, [
                'label' => 'Departamento',
                'placeholder' => 'Seleccione...'
            ])
            ->add('ciudad', null, [
                'placeholder' => 'Seleccione...'
            ])
            ->add('tipoVivienda', ChoiceType::class, [
                'choices' => array_flip(HvConstant::VIVIENDA_TIPO),
                'label' => 'Tipo de vivienda',
                'placeholder' => 'Seleccione...'
            ])
            ->add('tenedor', ChoiceType::class, [
                'choices' => array_flip(HvConstant::TENEDOR),
                'placeholder' => 'Seleccione...'
            ])
            ->add('viviendaActual', null, [
                'label' => 'Vivienda actual'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vivienda::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
