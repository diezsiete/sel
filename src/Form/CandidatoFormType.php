<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;

class CandidatoFormType extends AbstractType
{
    private $actividadChoices = [
        'Industrial y Logistica' => 'Industrial y Logistica',
        'Mercadeo' => 'Mercadeo',
        'Servicios Corporativos' => 'Servicios Corporativos',
        'Turismo y Hotelería' => 'Turismo y Hotelería'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [ new NotBlank(['message' => 'Ingrese su nombre'])],
            ])
            ->add('identificacion', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese su identificación']),
                    new Regex(["pattern" => "/^[0-9]+$/", "message" => "Solo se aceptan numeros"])
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese correo']),
                    new Email(['message' => 'Ingrese un correo valido'])
                ],
            ])
            ->add('telefono', TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese telefono']),
                    new Regex(["pattern" => "/^[0-9]+$/", "message" => "Solo se aceptan numeros"])
                ],
            ])
            ->add('residencia', TextType::class,[
                'constraints' => [ new NotBlank(['message' => 'Ingrese ciudad de residencia'])],
            ])
            ->add('nacimiento', DateType::class, [
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'help' => 'Utilice el formato: 2019-01-31',
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese fecha de nacimiento']),
                    new Date(['message' => 'Ingrese una fecha valida'])
                ],
            ])
            ->add('actividad', ChoiceType::class, [
                'choices' => $this->actividadChoices,
                'expanded' => true,
                'data' => 'Industrial y Logistica'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
