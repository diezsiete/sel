<?php

namespace App\Form;

use App\Entity\Usuario;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileFormType extends AbstractType
{


    /**
     * @var ContainerBagInterface
     */
    private $bag;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Security
     */
    private $security;

    public function __construct(ContainerBagInterface $bag, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->bag = $bag;
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array_keys($this->bag->get('security.role_hierarchy.roles'));
        $usuario = $options['data'] ?? null;

        $passwordConstraints = $usuario ? [] : [ new NotBlank(['message' => 'Ingrese contraseña']) ];

        $builder
            ->add('identificacion')
            ->add('email')
            ->add('primerNombre')
            ->add('segundoNombre')
            ->add('primerApellido')
            ->add('segundoApellido')
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir Contraseña'],
                'help' => 'Asigne aqui su nueva contraseña en caso que desee cambiarla',
                'required' => false,
                'invalid_message' => 'Las contraseñas deben coincidir',
                'constraints' => $passwordConstraints
            ]);

        if($this->security->isGranted(['ROLE_ALLOWED_TO_SWITCH'], $this->security->getUser())) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => array_combine(array_map(function ($rol) {
                    return trim(str_replace(['ROLE', '_'], ' ', $rol));
                }, $roles), $roles),
                'multiple' => true,
                'expanded' => true
            ]);
        }




        /*$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($usuario) {
            if(!$usuario && $event->getData()['plainPassword']['first'] && $event->getData()['plainPassword']['second']) {
                $event->getForm()->add('password', null, [
                    'data' => 'popo'
                ]);
            }
        });*/

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($usuario) {
            if(!$usuario) {
                $event->getData()->setPassword("undefined");
                $event->getData()->aceptarTerminos();
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($usuario) {
            if($event->getForm()->isValid()) {
                if($plainPassword = $event->getForm()['plainPassword']->getData()) {
                    $encodedPassword = $this->passwordEncoder->encodePassword($event->getData(), $plainPassword);
                    $event->getData()->setPassword($encodedPassword);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
