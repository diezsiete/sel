<?php

namespace App\Form;

use App\Entity\Vacante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacanteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', null, [
                'label' => 'Título'
            ])
            ->add('ciudad')
            ->add('descripcion')
            ->add('requisitos')
            ->add('area')
            ->add('cargo')
            ->add('nivel')
            ->add('subnivel')
            ->add('contratoTipo')
            ->add('intensidadHoraria')
            ->add('vacantesCantidad')

            ->add('salarioRango')
            ->add('salarioPublicar')
            ->add('salarioNeto')
            ->add('salarioAdicion')
            ->add('salarioAdicionConcepto')

            ->add('nivelAcademico')
            ->add('nivelAcademicoCurso')

            ->add('profesion')
            ->add('experiencia')

            ->add('idioma')
            ->add('idiomaDestreza')

            ->add('genero')

            ->add('licenciaConduccion')

            ->add('vigencia')
            ->add('empresa')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacante::class,
        ]);
    }
}
