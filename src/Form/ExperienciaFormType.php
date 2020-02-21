<?php

namespace App\Form;

use App\Constant\VacanteConstant;
use App\Entity\Hv\Experiencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienciaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa', null, [
                'label' => 'Nombre de la empresa'
            ])
            ->add('cargo', null, [
                'label' => 'Nombre del cargo'
            ])
            ->add('area', null, [
                'label' => 'Area del cargo',
                'placeholder' => 'Seleccione...'
            ])
            ->add('duracion', ChoiceType::class, [
                'label' => 'Duración',
                'placeholder' => 'Seleccione...',
                'choices' => array_flip(VacanteConstant::EXPERIENCIA)
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'required' => true,
            ])
            ->add('logrosObtenidos', null, [
                'label' => 'Logros obtenidos'
            ])
            ->add('motivoRetiro', null, [
                'label' => 'Motivo del retiro'
            ])
            ->add('jefeInmediato', null, [
                'label' => 'Jefe inmediato',
                'required' => true,
            ])
            ->add('telefonoJefe', null, [
                'label' => 'Teléfono contacto jefe',
                'required' => true,
            ])
            ->add('salarioBasico', TextType::class, [
                'label' => 'Salario básico'
            ])
            ->add('fechaIngreso', DateTimeType::class, [
                'label' => 'Fecha ingreso',
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
            ->add('fechaRetiro', DateTimeType::class, [
                'label' => 'Fecha retiro',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Experiencia::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
