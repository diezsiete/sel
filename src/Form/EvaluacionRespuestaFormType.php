<?php


namespace App\Form;



use App\Entity\Evaluacion\Respuesta\MultipleOrdenar;
use App\Entity\Evaluacion\Respuesta\MultipleUnica;
use App\Entity\Evaluacion\Pregunta\Opcion;
use App\Entity\Evaluacion\Respuesta\Opcion as RespuestaOpcion;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluacionRespuestaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var MultipleUnica $respuesta */
        $respuesta = $options['data'];

        if($respuesta instanceof MultipleOrdenar) {
            $builder->add('opciones', EvaluacionMultipleOrdenarType::class, [
                'choices' => $respuesta->getPregunta()->getOpciones(),
                'data' => $respuesta
            ]);
        } else {
            $builder->add('opciones', EntityType::class, [
                'class' => Opcion::class,
                'choices' => $respuesta->getPregunta()->getOpciones(),
                'multiple' => false,
                'expanded' => true,
            ]);

            $builder->get('opciones')->addModelTransformer(new CallbackTransformer(
                function ($collection) use ($respuesta) {
                    foreach($respuesta->getOpciones() as $respuestaOpcion) {
                        foreach($respuesta->getPregunta()->getOpciones() as $preguntaOpcion) {
                            if($respuestaOpcion->getPreguntaOpcion() === $preguntaOpcion) {
                                return $preguntaOpcion;
                            }
                        }
                    }
                    return null;
                },
                function ($preguntaOpcion) {
                    if($preguntaOpcion) {
                        return new ArrayCollection([(new RespuestaOpcion())->setPreguntaOpcion($preguntaOpcion)]);
                    }
                    return new ArrayCollection([]);
                }
            ));
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var Respuesta $respuesta */
            $respuesta = $event->getData();
            $respuesta->setRespondidaEn(new \DateTime());
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Respuesta::class,
        ]);
    }
}