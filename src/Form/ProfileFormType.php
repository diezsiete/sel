<?php

namespace App\Form;

use App\Entity\Usuario;
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
    /**
     * @var FormBuilderInterface
     */
    private $builder;
    /**
     * @var array
     */
    private $removeFields;

    public function __construct(ContainerBagInterface $bag, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->bag = $bag;
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->builder = $builder;
        $this->removeFields = $options['removeFields'];
        $this->buildFormInternal($builder, $options);

    }
    protected function buildFormInternal(FormBuilderInterface $builder, array $options)
    {
        $roles = $options['roles'];
        $singleRole = $options['singleRol'];
        $allowPasswordField = !in_array('password', $this->removeFields);

        $usuario = $options['data'] ?? null;

        $this
            ->add('identificacion')
            ->add('email')
            ->add('primerNombre')
            ->add('segundoNombre')
            ->add('primerApellido')
            ->add('segundoApellido');

        if($allowPasswordField) {
            $builder->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Repetir Contraseña'],
                'help' => 'Asigne aqui su nueva contraseña en caso que desee cambiarla',
                'required' => false,
                'invalid_message' => 'Las contraseñas deben coincidir',
                'constraints' => $usuario && $usuario->getId() ? [] : [ new NotBlank(['message' => 'Ingrese contraseña']) ]
            ]);
        }

        if($roles) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => array_combine(array_map(function ($rol) {
                    return trim(str_replace(['ROLE', '_'], ' ', $rol));
                }, $roles), $roles),
                'multiple' => !$singleRole,
                'expanded' => true,
            ]);
            if($singleRole){
                $builder->get('roles')->addModelTransformer($singleRole);
            }
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($usuario) {
            if(!$usuario || !$usuario->getId()) {
                $event->getData()->setPassword("undefined");
                $event->getData()->aceptarTerminos();
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($usuario, $allowPasswordField) {
            if($event->getForm()->isValid() && $allowPasswordField) {
                if($plainPassword = $event->getForm()['plainPassword']->getData()) {
                    $encodedPassword = $this->passwordEncoder->encodePassword($event->getData(), $plainPassword);
                    $event->getData()->setPassword($encodedPassword);
                    $event->getData()->setType(2);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $roles = [];
        if($this->security->isGranted(['ROLE_ALLOWED_TO_SWITCH'], $this->security->getUser())) {
            $roles = array_keys($this->bag->get('security.role_hierarchy.roles'));
        }
        $resolver->setDefaults([
            'data_class' => Usuario::class,
            'roles' => $roles,
            'singleRol' => false,
            'password' => true,
            'removeFields' => []
        ]);
    }

    public function add($child, $type = null, array $options = []) {
        if(!in_array($child, $this->removeFields)) {
            $this->builder->add($child, $type, $options);
        }
        return $this;
    }
}
