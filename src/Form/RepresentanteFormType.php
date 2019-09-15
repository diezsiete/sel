<?php


namespace App\Form;


use App\Entity\Representante;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RepresentanteFormType extends AbstractType
{
    /**
     * @var ContainerBagInterface
     */
    private $bag;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(ContainerBagInterface $bag, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->bag = $bag;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Representante $representante */
        $representante = $options['data'];

        $esNuevo = !$representante->getUsuario()->getId();

        $roles = $this->getRepresentanteRoles();

        $usuarioRemoveFields = ['password'];
        if(!$esNuevo) {
            $usuarioRemoveFields[] = 'email';
            $builder
                ->add('email', null, [
                    'data' => $representante->getUsuario()->getEmail()
                ])
                ->add('emailUnico', CheckboxType::class, [
                    'label' => 'Aplicar correo solo a este convenio',
                    'required' => false,
                    'mapped' => false
                ]);
        }

        $builder
            ->add('encargado', ChoiceType::class, [
                'label' => "Autoliquidaciones",
                'choices'  => [
                    'Encargado' => true,
                    'Bcc' => false,
                ],
                'expanded' => true,
                'data' => !!$representante->isEncargado(),
                "help" => "Es encargado de empleados o solo recibe copia de autoliquidaciones"
            ])
            ->add('usuario', ProfileFormType::class, [
                'data' => $representante ? $representante->getUsuario() : null,
                'roles' => $roles,
                'removeFields' => $usuarioRemoveFields,
                'singleRol' => new CallbackTransformer(
                    function ($rolesToTransform) use ($roles) {
                        $rolRepresentante = array_intersect($rolesToTransform, $roles);
                        return $rolRepresentante ? $rolRepresentante[array_key_first($rolRepresentante)] : '';
                    },
                    function ($rol) use ($representante) {
                        if($rol) {
                            // para al asignar roles de representante no sobreeescriba los que ya tiene
                            $roles = $representante->getUsuario()->getRoles();
                            array_push($roles, $rol);
                            return $roles;
                        }
                        return [];
                    }
                )
            ]);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($esNuevo, $representante) {
            if($esNuevo) {
                $event->getData()->setEmail($event->getData()->getUsuario()->getEmail());
            }
        });
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($esNuevo, $representante) {
            if($esNuevo && $event->getForm()->isValid()) {
                $event->getData()->getUsuario()->setPassword(
                    $this->passwordEncoder->encodePassword($event->getData()->getUsuario(), $event->getData()->getUsuario()->getEmail())
                );
                $event->getData()->setEmail($event->getData()->getUsuario()->getEmail());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Representante::class
        ]);
    }

    private function getRepresentanteRoles()
    {
        return array_filter(array_keys($this->bag->get('security.role_hierarchy.roles')), function($rol) {
            return preg_match('/REPRESENTANTE/', $rol);
        });
    }
}