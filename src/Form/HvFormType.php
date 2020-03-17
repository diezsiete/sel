<?php

namespace App\Form;

use App\Entity\Hv\EstadoCivil;
use App\Entity\Hv\FactorRh;
use App\Entity\Hv\Genero;
use App\Entity\Hv\GrupoSanguineo;
use App\Entity\Hv\IdentificacionTipo;
use App\Entity\Hv\Nacionalidad;
use App\Entity\Hv\NivelAcademico;
use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Main\Pais;
use App\Form\Model\HvDatosBasicosModel;
use App\Repository\Main\UsuarioRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @var \App\Repository\Main\UsuarioRepository
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
            ->add('identificacionTipo', EntityType::class, [
                'class' => IdentificacionTipo::class,
                'label' => 'Tipo de identificación',
                'required' => true
            ])
            ->add('identPais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'Pais de la identificación',
                'placeholder' => 'Seleccione...',
            ])
            ->add('nacPais', EntityType::class, [
                'class' => Pais::class,
                'label' => 'Pais de nacimiento',
                'placeholder' => 'Seleccione...'
            ])
            ->add('nacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'help' => 'Utilizar el formato ej: 2010-10-23',
            ])
            ->add('genero', EntityType::class, [
                'class' => Genero::class,
                'required' => true,
            ])
            ->add('estadoCivil', EntityType::class, [
                'class' => EstadoCivil::class,
                'label' => 'Estado civil',
                'required' => true,
            ])

            ->add('grupoSanguineo', EntityType::class, [
                'class' => GrupoSanguineo::class,
                'label' => 'Grupo sanguineo',
                'required' => true,
            ])
            ->add('factorRh', EntityType::class, [
                'class' => FactorRh::class,
                'label' => 'Factor RH',
                'required' => true,
            ])
            ->add('nacionalidad', EntityType::class, [
                'class' => Nacionalidad::class,
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
            ->add('barrio', null, [
                'required' => true,
            ])
            ->add('direccion', null, [
                'required' => true,
            ])
            ->add('telefono')
            ->add('celular')

            ->add('nivelAcademico', EntityType::class, [
                'class' => NivelAcademico::class,
                'required' => true,
            ])
            /*->add('estatura', null, [
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
            ])*/
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
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HvDatosBasicosModel::class,
            'csrf_protection' => false,
        ]);
    }

    private function setupDptoField(FormInterface $form, ?Pais $pais, array $locationConfig)
    {
        $options = [
            'label' => $locationConfig['label'],
            'placeholder' => 'Seleccione...',
            'required' => false,
            'auto_initialize' => false,
        ];
        $type = EntityType::class;
        if ($pais && $dptos = $pais->getDptos()) {
            $options += [
                'class' => Dpto::class,
                'choices' => $dptos
            ];
        } else {
            $type = ChoiceType::class;
            $options['disabled'] = true;
            $options['choices'] = [];
        }
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder($locationConfig['name'], $type, null, $options);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $config = self::LOCACION_FIELD_CONFIG[str_replace('Dpto', 'Pais', $form->getName())];
            $dpto = $form->getData();
            $this->setupCiudadField($form->getParent(), $dpto, $config['ciudad']);
        });
        $form->add($builder->getForm());
    }

    private function setupCiudadField(FormInterface $form, ?Dpto $dpto, array $locationConfig)
    {
        $options = [
            'label' => $locationConfig['label'],
            'required' => false,
        ];
        $type = EntityType::class;
        if ($dpto) {
            $options += [
                'class' => Ciudad::class,
                'query_builder' => function(EntityRepository $er) use ($dpto) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.dpto = :dpto')
                        ->setParameter('dpto', $dpto);
                }
            ];
        } else {
            $type = ChoiceType::class;
            //$options['disabled'] = true;
            $options['choices'] = [];
        }
        $form->add($locationConfig['name'], $type, $options);
    }
}
