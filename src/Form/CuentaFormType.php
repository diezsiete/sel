<?php

namespace App\Form;


use App\Entity\Usuario;
use App\Form\DataTransformer\AceptoTerminosEnTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class CuentaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacion', null, [
                'label' => 'Identificación'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir Contraseña'],
                'help' => 'Asigne aqui su nueva contraseña para acceder a la pagina',
                'required' => true,
                'invalid_message' => 'Las contraseñas deben coincidir',
            ])
            ->add('aceptoTerminosEn', CheckboxType::class, [
                'label' => 'Acepto los terminos y condiciones',
                'help' => 'Con el diligenciamiento y envío del presente formulario declaro que autorizo el tratamiento de mis Datos
                Personales por parte de PTA,  de acuerdo con las finalidades establecidas en su
                <a href="http://localhost/SE2017/pta/pages/blog/POLITICA-TRATAMIENTO-DATOS-PERSONALES.pdf" target="_blank">
                    Política de Tratamiento de Datos Personales
                </a>',
                'help_html' => true,
                'required' => false,
                'invalid_message' => 'Debe aceptar los terminos'
            ])
        ;
        $builder->get('aceptoTerminosEn')->addModelTransformer(new AceptoTerminosEnTransformer());

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Usuario $usuario */
            $usuario = $event->getData();
            $usuario->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setType(2);
        });
    }
}
