<?php

namespace App\Form;

use App\Constant\HvConstant;
use App\Entity\Ciudad;
use App\Entity\Dpto;
use App\Entity\Hv;
use App\Entity\Pais;
use App\Form\Model\HvDatosBasicosModel;
use App\Repository\UsuarioRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
        $hvdto = $options['data'] ?? null;

        $builder
            ->add('primerNombre', TextType::class, [
                'label' => 'Primer nombre',
                'required' => true,
            ])
            ->add('segundoNombre', TextType::class, [
                'label' => 'Segundo nombre',
                'required' => false,
            ])
            ->add('primerApellido', TextType::class, [
                'label' => 'Primer apellido',
                'required' => true,
            ])
            ->add('segundoApellido', TextType::class, [
                'label' => 'Segundo apellido',
                'required' => false,
            ])
            ->add('identificacion', TextType::class, [
                'label' => 'Identificación',
                'required' => true,
            ])

            ->add('email', EmailType::class, [
                'required' => true,
            ])

            ->add('identificacionTipo', ChoiceType::class, [
                'choices' => array_flip(HvConstant::IDENTIFICACION_TIPO),
                'label' => 'Tipo de identificación',
                'required' => true
            ])
            ->add('identPais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'Pais de la identificación',
                'placeholder' => 'Seleccione...',
            ])
            ->add('identDpto')

            ->add('nacPais', EntityType::class, [
                'class' => Pais::class,
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
                'choices' => array_flip(HvConstant::GENERO),
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

            ->add('emailAlt', EmailType::class, [
                'label' => 'Email alternativo',
                'required' => false,
            ])

            ->add('resiPais', EntityType::class, [
                'class' => Pais::class,
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

            ->add('nivelAcademico', ChoiceType::class, [
                'choices' => array_flip(HvConstant::NIVEL_ACADEMICO)
            ])
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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($hvdto) {
            foreach(self::LOCACION_FIELD_CONFIG as $paisName => $config) {
                $pais = $hvdto ? $hvdto->{"get".ucfirst($paisName)}() : null;
                $this->setupDptoField($event->getForm(), $pais, $config['dpto']);

                $dpto = $hvdto ? $hvdto->{"get".ucfirst($config['dpto']['name'])}() : null;
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
            'data_class' => HvDatosBasicosModel::class,
        ]);
    }

    private function setupDptoField(FormInterface $form, ?Pais $pais, array $locationConfig)
    {
        $this->setupLocationField($form, !is_object($pais), $locationConfig + ['class' => Dpto::class]);
    }

    private function setupCiudadField(FormInterface $form, ?Dpto $dpto, array $locationConfig)
    {
        $this->setupLocationField($form, !is_object($dpto), $locationConfig + ['class' => Ciudad::class]);
    }


    private function setupLocationField(FormInterface $form, bool $disabled, array $locationConfig)
    {
        $options = [
            'label' => $locationConfig['label'],
            'placeholder' => 'Seleccione...',
            'required' => false,
            'class' => $locationConfig['class'],
        ];
        $type = EntityType::class;
        if ($disabled) {
            $type = ChoiceType::class;
            $options['disabled'] = true;
            $options['choices'] = [];
            unset($options['class']);
        }
        $form->add($locationConfig['name'], $type, $options);
    }
}
