<?php


namespace App\Form;


use App\Entity\Evaluacion\Pregunta\Opcion;
use App\Entity\Evaluacion\Respuesta\MultipleOrdenar;
use App\Entity\Evaluacion\Respuesta\MultipleUnica;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluacionMultipleOrdenarType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var MultipleOrdenar
     */
    private $respuesta;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->respuesta = $options['data'];

        /** @var Opcion[] $choices */
        $choices = $options['choices'];
        $totalChoices = count($choices);
        $ordenChoice = [];
        for($i = 1; $i <= $totalChoices; $i++) {
            $ordenChoice[$i] = $i;
        }

        foreach($choices as $choice) {
            $index = $choice->getRespuesta();
            $builder->add('opcionPregunta' . $index, ChoiceType::class, [
                'choices' => $ordenChoice,
                'label' => $choice->getTexto(),
            ]);
            $builder->get('opcionPregunta' . $index)->addModelTransformer($this);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var MultipleUnica $respuesta */
            $respuesta = $event->getData();
            $respuesta->removeOpciones();
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [],
            'data' => null,
        ]);
    }

    /**
     * @param Opcion $value
     * @return mixed
     */
    public function transform($value)
    {
        return $value->getIndice();
    }

    public function reverseTransform($value)
    {
        return $value;
    }
}