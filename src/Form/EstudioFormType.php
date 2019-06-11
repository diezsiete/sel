<?php

namespace App\Form;

use App\Entity\Estudio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstudioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', null, [
                'label' => 'Area de estudio',
                'placeholder' => 'Seleccione...'
            ])
            ->add('nombre', null, [
                'label' => 'Titulo del estudio'
            ])
            ->add('instituto', null, [
                'label' => 'Institución',
                'placeholder' => 'Seleccione...',
                'help' => 'Si no encuentra la institucón, seleccione la opción "NO APLICA"'
            ])
            ->add('institutoNombreAlt', null, [
                'label' => 'Nombre de la institución',
                'help' => 'Ingrese el nombre de la institución que no encuentra en la lista "Institución"'
            ])
            ->add('fin',DateType::class, [
                'label' => 'Fecha de finalización',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
            ->add('graduado')
            ->add('cancelo')
//            ->add('anoEstudio')
//            ->add('horasEstudio')
//            ->add('semestresAprobados')
//            ->add('numeroTarjeta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Estudio::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
