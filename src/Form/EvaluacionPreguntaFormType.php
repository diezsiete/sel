<?php


namespace App\Form;


use App\Entity\Evaluacion\Pregunta\MultipleUnica;
use App\Entity\Evaluacion\Pregunta\Opcion;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluacionPreguntaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var MultipleUnica $pregunta */
        $pregunta = $options['data'];



        $builder->add('opciones', EntityType::class, [
            'class' => Opcion::class,
            'choices' => $pregunta->getOpciones(),
            'multiple' => false,
            'expanded' => true,
        ]);


        $builder->get('opciones')->addModelTransformer(new CallbackTransformer(
            function ($collection) {
                return null;
            },
            function ($id) {
                if($id) {
                    // TODO
                    return new ArrayCollection([$id]);
                }
                return new ArrayCollection([]);
            }
        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pregunta::class,
        ]);
    }
}