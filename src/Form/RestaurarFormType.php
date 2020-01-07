<?php


namespace App\Form;


use App\Entity\Main\RestaurarClave;
use App\Entity\Main\Usuario;
use App\Form\DataTransformer\RestaurarClaveIdentificacionTransformer;
use App\Repository\Main\UsuarioRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RestaurarFormType extends AbstractType
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => 'Contraseña'],
            'second_options' => ['label' => 'Repetir Contraseña'],
            'help' => 'Asigne aqui su nueva contraseña para acceder a la pagina',
            'required' => true,
            'invalid_message' => 'Las contraseñas deben coincidir',
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            if($event->getForm()->isValid()) {
                /** @var Usuario $usuario */
                $usuario = $event->getData();
                $usuario
                    ->setPassword($this->passwordEncoder->encodePassword($usuario, $usuario->getPassword()))
                    ->setType(2);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class
        ]);
    }
}