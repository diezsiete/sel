<?php


namespace App\Form;


use App\Form\DataTransformer\AceptoTerminosEnTransformer;
use App\Service\Configuracion\Configuracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AceptoTerminosEnType extends AbstractType
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    public function getParent()
    {
        return CheckboxType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new AceptoTerminosEnTransformer());
    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['help'] = str_replace(['%empresa%', '%politica_route%'], [$options['empresa'], $options['politica_route']], $options['help']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Acepto los terminos y condiciones',
            'help_html' => true,
            'required' => false,
            'invalid_message' => 'Debe aceptar los terminos',
            'help' => 'Con el diligenciamiento y envío del presente formulario declaro que autorizo el tratamiento de mis Datos
                Personales por parte de %empresa%,  de acuerdo con las finalidades establecidas en su
                <a href="%politica_route%" target="_blank">
                    Política de Tratamiento de Datos Personales
                </a>',
            'empresa' => $this->configuracion->getRazon(),
            'politica_route' => '#'
        ]);
    }

}
