<?php

namespace App\Form;

use App\Constant\VacanteConstant;
use App\Entity\Idioma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdiomaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idiomaCodigo', ChoiceType::class, [
                'choices' => array_flip(VacanteConstant::IDIOMA_CODIGO),
                'label' => 'Idioma',
                'placeholder' => 'Seleccione...'
            ])
            ->add('destreza', ChoiceType::class, [
                'choices' => array_flip(VacanteConstant::IDIOMA_DESTREZA),
                'placeholder' => 'Seleccione...'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Idioma::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
