<?php

namespace App\Form;

use App\Form\Model\ContactoModel;
use App\Service\Configuracion\Configuracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactoFormType extends AbstractType
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
        $to = $options['to'];

        $builder
            ->add('nombre')
            ->add('from', EmailType::class, [
                'label' => 'Correo electrónico'
            ])
            ->add('mensaje', TextareaType::class);

        if(is_array($to)) {
            $asuntos = array_keys($to);
            $builder->add('asunto', ChoiceType::class, [
                'choices' => array_combine($asuntos, $asuntos),
            ]);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($to) {
            /** @var ContactoModel $contacto */
            $contacto = $event->getData();
            $contacto->to = is_array($to) ? $to[$contacto->asunto] : $to;
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactoModel::class,
            'to' => $this->configuracion->getEmails()->getContacto(),
        ]);
    }
}
