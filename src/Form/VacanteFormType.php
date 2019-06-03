<?php

namespace App\Form;

use App\Constant\VacanteConstant;
use App\Entity\Ciudad;
use App\Entity\Idioma;
use App\Entity\LicenciaConduccion;
use App\Entity\Vacante;
use App\Repository\CiudadRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacanteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', null, [
                'label' => 'Título'
            ])
            ->add('ciudad', EntityType::class, [
                'class' => Ciudad::class,
                'query_builder' => function (CiudadRepository $repository) {
                    return $repository->createQueryBuilder('c')->addCriteria($repository->ciudadesColombiaCriteria());
                },
                'multiple' => true,
                'required' => true,
            ])
            ->add('descripcion')
            ->add('requisitos')
            ->add('area', null, [
                'required' => true,
            ])
            ->add('cargo', null, [
                'required' => true,
            ])
            ->add('nivel', ChoiceType::class, [
                'choices' => array_flip(VacanteConstant::NIVEL),
                'placeholder' => 'Seleccione...'
            ])
            ->add('contratoTipo', ChoiceType::class, [
                'label' => 'Tipo de contrato',
                'placeholder' => 'Seleccione...',
                'choices' => array_flip(VacanteConstant::CONTRATO_TIPO),
                'required' => false,
            ])
            ->add('intensidadHoraria', ChoiceType::class, [
                'label' => 'Intensidad horaria',
                'placeholder' => 'Seleccione...',
                'choices' => array_flip(VacanteConstant::INTENSIDAD_HORARIA),
            ])
            ->add('vacantesCantidad', null, [
                'label' => 'Cantidad de vacantes'
            ])
            ->add('salarioRango', ChoiceType::class, [
                'label' => 'Rango salario (en millones)',
                'placeholder' => 'Seleccione...',
                'choices' => array_flip(VacanteConstant::RANGO_SALARIO),
            ])
            ->add('salarioPublicar', CheckboxType::class, [
                'label' => 'Publicar salario',
            ])
            ->add('salarioNeto', null, [
                'label' => 'Salario neto',
                'help' => 'Para indicar el salario especifico de la vacante. Inserte el salario sin puntos ni comas',
                'required' => false
            ])
            ->add('salarioAdicion', null, [
                'label' => 'Salario adicionales',
                'help' => 'Utilice en casos para especificar una suma adicional al salario básico. Inserte sin puntos ni comas',
            ])
            ->add('salarioAdicionConcepto', null, [
                'label' => 'Concepto (Adicionales)',
                'help' => 'Dar una pequeña descripción de la razón de la suma adicional',
            ])
            ->add('nivelAcademico', null, [
                'label' => 'Minimo nivel academico'
            ])
            ->add('nivelAcademicoCurso', null, [
                'label' => 'Nivel academico en curso',
                'help' => 'Activado indica que la persona puede estar cursando el nivel académico indicado'
            ])
            ->add('profesion', null, [
                'label' => 'Profesión'
            ])
            ->add('experiencia', ChoiceType::class, [
                'label' => 'Años de experiencia requerida',
                'choices' => array_flip(VacanteConstant::EXPERIENCIA),
            ])
            ->add('idioma', null, [
                'label' => 'Idioma requerido',
                'help' => 'Si la vacante tiene como requerimiento el manejo de algún idioma',
                'placeholder' => 'Seleccione...'
            ])
            ->add('genero', ChoiceType::class, [
                'label' => 'Genero',
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'No aplica',
                'choices' => array_flip(VacanteConstant::GENERO),
                'required' => false,
            ])
            ->add('licenciaConduccion', EntityType::class, [
                'label' => 'Licencia de conducción',
                'class' => LicenciaConduccion::class,
                'expanded' => true,
                'multiple' => true,
                'placeholder' => 'No aplica',
                'required' => false,
            ])
            ->add('vigencia', null, [
                'label' => 'Vigencia',
                'help' => 'Duración publicada en la página'
            ])
            ->add('empresa', ChoiceType::class, [
                'label' => 'Empresa (Novasoft)',
                'choices' => array_flip(VacanteConstant::EMPRESA),
                'help' => 'A que base de datos de Novasoft se subiran las hojas de vida'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Vacante $vacante */
            if ($vacante = $event->getData()) {
                $this->setupSubnivelField($event->getForm(), $vacante->getNivel());
                $this->setupIdiomaDestrezaField($event->getForm(), $vacante->getIdioma());
            }
        });
        $builder->get('nivel')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $this->setupSubnivelField($form->getParent(), $form->getData());
        });
        $builder->get('idioma')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $this->setupIdiomaDestrezaField($form->getParent(), $form->getData());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacante::class,
        ]);
    }

    private function setupSubnivelField(FormInterface $form, ?int $nivel)
    {
        $subniveles = $nivel ? VacanteConstant::SUBNIVEL[$nivel] : [];

        $options = [
            'choices' => array_flip($subniveles),
            'required' => true,
            'disabled' => !$subniveles,
        ];
        if (!$subniveles) {
            $options['placeholder'] = 'Seleccione Nivel...';
        }
        $form->add('subnivel', ChoiceType::class, $options);
    }

    private function setupIdiomaDestrezaField(FormInterface $form, ?Idioma $idioma)
    {
        $options = [
            'label' => 'Destreza en el idioma',
            'help' => 'Seleccione el nivel de destreza que se require en el idioma',
            'choices' => array_flip(VacanteConstant::IDIOMA_DESTREZA),
            'required' => false,
        ];
        if(!$idioma) {
            $options['disabled'] = true;
        } else {
            $options['placeholder'] = false;
        }
        $form->add('idiomaDestreza', ChoiceType::class, $options);
    }
}
