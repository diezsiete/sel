<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\Entity\Dpto;
use App\Entity\Hv;
use App\Entity\Pais;
use App\Repository\UsuarioRepository;
use App\Validator\IdentificacionUnica;
use App\Validator\IdentificacionUnicaValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class HvFormType extends AbstractType
{
    const LOCACION_FIELD_CONFIG = [
        'identPais' => [
            'dpto' => [
                'label' => 'Departamento de la identificacion',
                'name' => 'identDpto',
            ],
            'ciudad' => [
                'label' => 'Ciudad de la identificación',
                'name' => 'identCiudad',
            ]
        ],
        'nacPais' => [
            'dpto' => [
                'label' => 'Departamento de nacimiento',
                'name' => 'nacDpto',
            ],
            'ciudad' => [
                'label' => 'Ciudad de nacimiento',
                'name' => 'nacCiudad',
            ]
        ],
        'resiPais' => [
            'dpto' => [
                'label' => 'Departamento donde vive',
                'name' => 'resiDpto',
            ],
            'ciudad' => [
                'label' => 'Ciudad donde vive',
                'name' => 'resiCiudad',
            ]
        ]
    ];
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {

        $this->usuarioRepository = $usuarioRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('primerNombre', TextType::class, [
                'label' => 'Primer nombre',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese primer nombre'])
                ]
            ])
            ->add('segundoNombre', TextType::class, [
                'label' => 'Segundo nombre',
                'mapped' => false,
                'required' => false,
            ])
            ->add('primerApellido', TextType::class, [
                'label' => 'Primer apellido',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese primer apellido'])
                ]
            ])
            ->add('segundoApellido', TextType::class, [
                'label' => 'Segundo apellido',
                'mapped' => false,
                'required' => false,
            ])
            ->add('identificacion', NumberType::class, [
                'html5' => false,
                'label' => 'Identificación',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese identificación']),
                    new IdentificacionUnica(),
                ]
            ])
            ->add('identificacionTipo', ChoiceType::class, [
                'choices' => array_flip(HvConstant::IDENTIFICACION_TIPO),
                'label' => 'Tipo de identificación',
                'required' => true
            ])
            ->add('identPais', null, [
                'label' => 'Pais de la identificación',
                'placeholder' => 'Seleccione...',
            ])
            ->add('identDpto')

            ->add('nacPais', null, [
                'label' => 'Pais de nacimiento',
                'placeholder' => 'Seleccione...'
            ])
            ->add('nacDpto')


            ->add('nacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
            ->add('genero', ChoiceType::class, [
                'choices' => array_flip(VacanteConstant::GENERO),
                'required' => true,
            ])
            ->add('estadoCivil', ChoiceType::class, [
                'choices' => array_flip(HvConstant::ESTADO_CIVIL),
                'label' => 'Estado civil',
                'required' => true,
            ])

            ->add('grupoSanguineo', ChoiceType::class, [
                'choices' => array_flip(HvConstant::GRUPO_SANGUINEO),
                'label' => 'Grupo sanguineo',
                'required' => true,
            ])
            ->add('factorRh', ChoiceType::class, [
                'choices' => array_flip(HvConstant::FACTOR_RH),
                'label' => 'Factor RH',
                'required' => true,
            ])
            ->add('nacionalidad', ChoiceType::class, [
                'choices' => array_flip(HvConstant::NACIONALIDAD),
                'required' => true,
            ])


            ->add('email', EmailType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ingrese email']),
                    new Email(['message' => 'Ingrese un email valido'])
                ]
            ])
            ->add('emailAlt', EmailType::class, [
                'label' => 'Email alternativo',
                'required' => false,
            ])

            ->add('resiPais', null, [
                'label' => 'Pais donde vive',
                'placeholder' => 'Seleccione...'
            ])
            ->add('resiDpto')

            ->add('barrio', null, [
                'required' => true,
            ])
            ->add('direccion', null, [
                'required' => true,
            ])
            ->add('telefono')
            ->add('celular')

            ->add('nivelAcademico')

            ->add('estatura', null, [
                'label' => 'Estatura (Metros)'
            ])
            ->add('peso', null, [
                'label' => 'Peso (Kilogramos)'
            ])
            ->add('aspiracionSueldo', null, [
                'label' => 'Aspiración sueldo'
            ])
            ->add('personasCargo', null, [
                'label' => 'Personas a cargo'
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Hv|null $hv */
            $hv = $event->getData();
            foreach(self::LOCACION_FIELD_CONFIG as $paisName => $config) {
                $pais = $hv ? $hv->{"get".ucfirst($paisName)}() : null;
                $this->setupDptoField($event->getForm(), $pais, $config['dpto']);

                $dpto = $hv ? $hv->{"get".ucfirst($config['dpto']['name'])}() : null;
                $this->setupCiudadField($event->getForm(), $dpto, $config['ciudad']);
            }
        });

        foreach(self::LOCACION_FIELD_CONFIG as $paisName => $config) {
            $builder->get($paisName)->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $config = self::LOCACION_FIELD_CONFIG[$form->getName()];
                $this->setupDptoField($form->getParent(), $form->getData(), $config['dpto']);
            });
            $builder->get($config['dpto']['name'])->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $config = self::LOCACION_FIELD_CONFIG[$form->getName()];
                $this->setupDptoField($form->getParent(), $form->getData(), $config['ciudad']);
            });
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hv::class,
        ]);
    }

    private function setupDptoField(FormInterface $form, ?Pais $pais, array $locationConfig)
    {
        $this->setupLocationField($form, !is_object($pais), $locationConfig);
    }

    private function setupCiudadField(FormInterface $form, ?Dpto $dpto, array $locationConfig)
    {
        $this->setupLocationField($form, !is_object($dpto), $locationConfig);
    }


    private function setupLocationField(FormInterface $form, bool $disabled, array $locationConfig)
    {
        $options = [
            'label' => $locationConfig['label'],
            'placeholder' => 'Seleccione...',
            'required' => false,
        ];
        $type = null;
        if ($disabled) {
            $type = ChoiceType::class;
            $options['disabled'] = true;
            $options['choices'] = [];
        }
        $form->add($locationConfig['name'], $type, $options);
    }
}
