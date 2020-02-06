<?php


namespace App\Form;


use App\Entity\Main\Representante;
use Symfony\Component\Security\Core\Security;
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
        /** @var Representante $representante */
        $representante = $options['data'];

        $esNuevo = !$representante->getUsuario()->getId();

        $esAdmin = $this->security->isGranted('ROLE_ADMIN');

        $roles = $esAdmin ? $this->getRepresentanteRoles() : [];

        $usuarioRemoveFields = ['password'];
        if(!$esNuevo) {
            $usuarioRemoveFields[] = 'email';
            $builder
                ->add('email', null, [
                    'data' => $representante->getUsuario()->getEmail()
                ]);
            if($esAdmin) {
                $this->add('emailTodos', CheckboxType::class, [
                    'label' => 'Aplicar correo a todos los convenios',
                    'required' => false,
                    'mapped' => false
                ]);
            }
        }

        if($this->security->isGranted(['ROLE_ADMIN', 'ROLE_ADMIN_AUTOLIQUIDACIONES'])) {
            $builder
                ->add('encargado', ChoiceType::class, [
                    'label' => 'Autoliquidaciones',
                    'choices' => [
                        'Encargado' => true,
                        'Bcc' => false,
                    ],
                    'expanded' => true,
                    'data' => !!$representante->isEncargado(),
                    'help' => 'Es encargado de empleados o solo recibe copia de autoliquidaciones'
                ]);
        }
        if($this->security->isGranted(['ROLE_ADMIN', 'ROLE_ARCHIVO_CARGAR'])) {
            $archivoChoices = $esAdmin ? ['Sin definir' => null] : [];
            $archivoChoices += [
                'Archivo Principal' => true,
                'Archivo Bcc' => false,
            ];
            $builder
                ->add('archivo', ChoiceType::class, [
                    'label' => 'Archivo',
                    'choices' => $archivoChoices,
                    'expanded' => true,
                    // si es role archivo cargar, y rep. no tiene asignado valor de archivo, seleccionamos default arhivo
                    'data' => !$esAdmin && $representante->whichArchivo() === null ? true : $representante->whichArchivo(),
                    'help' => 'Es destinatario principal de correo de archivo o copia oculta'
                ]);
        }


        $builder->add('usuario', ProfileFormType::class, [
            'data' => $representante ? $representante->getUsuario() : null,
            'roles' => $roles,
            'removeFields' => $usuarioRemoveFields,
            'singleRol' => new CallbackTransformer(
                function ($rolesToTransform) use ($roles) {
                    $rolRepresentante = array_intersect($rolesToTransform, $roles);
                    return $rolRepresentante ? $rolRepresentante[array_key_first($rolRepresentante)] : '';
                },
                function ($rol) use ($representante) {
                    if ($rol) {
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