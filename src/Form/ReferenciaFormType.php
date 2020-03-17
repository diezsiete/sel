<?php

namespace App\Form;

use App\Entity\Hv\Referencia;
use App\Entity\Hv\ReferenciaTipo;
use App\Service\Configuracion\Configuracion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenciaFormType extends AbstractType
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', EntityType::class, [
                'label' => 'Tipo de referencia',
                'class' => ReferenciaTipo::class,
                'placeholder' => 'Seleccione...'
            ])
            ->add('nombre', null, [
                'label' => 'Nombre completo'
            ])
            ->add('ocupacion', null, [
                'label' => 'Ocupación'
            ])
            ->add('parentesco', null, [
                'required' => true,
            ])
            ->add('celular', null, [
                'required' => true,
            ])
            ->add('telefono', null, [
                'required' => true,
            ])
            ->add('direccion', null, [
                'label' => 'Dirección',
            ])
            //->add('entidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Referencia::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
