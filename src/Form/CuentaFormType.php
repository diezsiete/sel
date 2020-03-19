<?php

namespace App\Form;


use App\Entity\Main\Usuario;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Regex;


class CuentaFormType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacion', null, [
                'label' => 'Identificación',
                'constraints' => [
                    new Regex(['pattern' => '/^[0-9]+$/', 'message' => 'Solo se aceptan numeros'])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir Contraseña'],
                'help' => 'Asigne aqui su nueva contraseña para acceder a la pagina',
                'required' => true,
                'invalid_message' => 'Las contraseñas deben coincidir',
            ])
            ->add('aceptoTerminosEn', AceptoTerminosEnType::class, [
                'politica_route' => $this->urlGenerator->generate('politica')
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Usuario $usuario */
            $usuario = $event->getData();
            $usuario->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
                ->setType(2);
        });
    }
}
