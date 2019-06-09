<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Entity\Referencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenciaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', ChoiceType::class, [
                'label' => 'Tipo de referencia',
                'choices' => array_flip(HvConstant::REFERENCIA_TIPO),
                'placeholder' => 'Seleccione...'
            ])
            ->add('nombre', null, [
                'label' => 'Nombre completo'
            ])
            ->add('ocupacion', null, [
                'label' => 'Ocupación'
            ])
            ->add('parentesco', null, [
                'required' => true,
            ])
            ->add('celular', null, [
                'required' => true,
            ])
            ->add('telefono', null, [
                'required' => true,
            ])
            ->add('direccion', null, [
                'label' => 'Dirección',
            ])
            ->add('entidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Referencia::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
