<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Entity\Hv\RedSocial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedSocialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', ChoiceType::class, [
                'choices' => array_flip(HvConstant::RED_SOCIAL),
                'label' => 'Tipo de red social',
                'placeholder' => 'Seleccione...'
            ])
            ->add('cuenta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RedSocial::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
