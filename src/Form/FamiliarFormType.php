<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\Entity\Familiar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamiliarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, [
                'label' => 'Nombres'
            ])
            ->add('primerApellido', null, [
                'label' => 'Primer apellido'
            ])
            ->add('segundoApellido', null, [
                'label' => 'Segundo apellido'
            ])
            ->add('identificacion', null, [
                'label' => 'Identificación'
            ])
            ->add('identificacionTipo', ChoiceType::class, [
                'choices' => array_flip(HvConstant::IDENTIFICACION_TIPO),
                'label' => 'Tipo de identificación',
                'placeholder' => 'Seleccione...'
            ])
            ->add('nacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
            ->add('parentesco', ChoiceType::class, [
                'choices' => array_flip(HvConstant::PARENTESCO),
                'placeholder' => 'Seleccione...'
            ])
            ->add('ocupacion', ChoiceType::class, [
                'choices' => array_flip(HvConstant::OCUPACION),
                'label' => 'Ocupación',
                'placeholder' => 'Seleccione...'
            ])
            ->add('nivelAcademico', ChoiceType::class, [
                'choices' => array_flip(HvConstant::NIVEL_ACADEMICO),
                'label' => 'Nivel académico',
                'placeholder' => 'Seleccione...'
            ])
            ->add('genero', ChoiceType::class, [
                'choices' => array_flip(HvConstant::GENERO),
                'placeholder' => 'Seleccione...'
            ])
            ->add('estadoCivil', ChoiceType::class, [
                'choices' => array_flip(HvConstant::ESTADO_CIVIL),
                'label' => 'Estado civil',
                'placeholder' => 'Seleccione...'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Familiar::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
