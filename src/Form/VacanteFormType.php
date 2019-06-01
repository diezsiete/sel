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
            ->add('date')
            ->add('titulo')
            ->add('descripcion')
            ->add('requisitos')
            ->add('vacantesCantidad')
            ->add('salarioNeto')
            ->add('salarioAdicion')
            ->add('salarioAdicionConcepto')
            ->add('salarioPublicar')
            ->add('nivelAcademicoCurso')
            ->add('idiomaPorcentaje')
            ->add('genero')
            ->add('publicada')
            ->add('empresa')
            ->add('nivel')
            ->add('subnivel')
            ->add('contratoTipo')
            ->add('intensidadHoraria')
            ->add('salarioRango')
            ->add('nivelAcademico')
            ->add('experiencia')
            ->add('idioma')
            ->add('usuario')
            ->add('area')
            ->add('cargo')
            ->add('ciudad')
            ->add('licenciaConduccion')
            ->add('profesion')
            ->add('aplicantes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacante::class,
        ]);
    }
}
