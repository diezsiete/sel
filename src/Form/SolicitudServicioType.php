<?php


namespace App\Form;


use App\Entity\Main\SolicitudServicio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreEmpresa', null, [
                'label' => 'Nombre de la empresa'
            ])
            ->add('sector', null, [
                'label' => 'Sector'
            ])
            ->add('servicio', ChoiceType::class, [
                'label' => 'Servicio que necesita',
                'expanded' => true,
                'choices' => array_flip(SolicitudServicio::SERVICIO_TYPE)
            ])
            ->add('nombreContacto', null, [
                'label' => 'Nombre contacto'
            ])
            ->add('emailCorporativo', EmailType::class, [
                'label' => 'Email corporativo'
            ])
            ->add('telefonoContacto', null, [
                'label' => 'Teléfono contacto'
            ])
            ->add('comentario', null, [
                'label' => 'Comentario'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudServicio::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}