<?php

namespace App\Form;

use App\Entity\RestaurarClave;
use App\Form\DataTransformer\RestaurarClaveIdentificacionTransformer;
use App\Repository\UsuarioRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OlvidoFormType extends AbstractType
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario', TextType::class, [
                'label' => 'Identificación',
                'invalid_message' => 'Usuario no encontrado'
            ]);
        $builder->get('usuario')->addModelTransformer(
            new RestaurarClaveIdentificacionTransformer($this->usuarioRepository));

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var RestaurarClave $restaurarClave */
            $restaurarClave = $event->getData();
            if ($restaurarClave->getUsuario()) {
                $restaurarClave->setToken($this->passwordEncoder->encodePassword(
                    $restaurarClave->getUsuario(), $restaurarClave->getUsuario()->getIdentificacion()));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurarClave::class
        ]);
    }
}