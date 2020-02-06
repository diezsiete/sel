<?php

namespace App\Form;

use App\Entity\Main\SolicitudServicio;
use App\Form\Model\ContactoModel;
use App\Service\Configuracion\Configuracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

class ContactoFormType extends AbstractType
{
    /**
     * @var Configuracion
     */
    private $configuracion;

    private $isContacto = true;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $to = $options['to'];
        $dataClass = $options['data_class'];

        if(is_array($to)) {
            $asuntos = array_keys($to);
            $builder->add('asunto', ChoiceType::class, [
                'choices' => array_combine($asuntos, $asuntos),
                'mapped' => $dataClass === ContactoModel::class
            ]);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var ContactoModel $data */
                $data = $event->getData();
                $asunto = $data->asunto;
                $this->formModifier($event->getForm(), $asunto);
            });

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $this->isContacto = $event->getData()['asunto'] !== "Solicitud servicios";
                if(!$this->isContacto) {
                    $event->getForm()->remove('nombre')->remove('from')->remove('mensaje');
                }
            });

            $builder->get('asunto')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $this->formModifier($event->getForm()->getParent(), $event->getData());
            });
        } else {
            $builder
                ->add('nombre', null, [
                    'constraints' => [
                        new NotBlank(['message' => "Ingrese su nombre"])
                    ]
                ])
                ->add('from', EmailType::class, [
                    'label' => 'Correo electrónico',
                    'constraints' => [
                        new NotBlank(['message' => "Ingrese su correo"]),
                        new Email(['message' => "Ingrese un email valido"])
                    ]
                ])
                ->add('mensaje', TextareaType::class, [
                    'constraints' => [
                        new NotBlank(['message' => "Ingrese mensaje"])
                    ]
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

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars['contacto'] = $this->isContacto;
    }

    protected function formModifier(FormInterface $form, ?string $asunto = null)
    {
        if($asunto === 'Solicitud servicios') {
            $form->add('solicitudServicio', SolicitudServicioType::class, [
                'data' => new SolicitudServicio(),
                'constraints' => [
                    new Valid()
                ]
            ]);
            $this->isContacto = false;
        } else {
            $this->buildDefault($form);
        }
    }
    
    protected function buildDefault(FormInterface $builder)
    {
        $builder
            ->add('nombre', null, [
                'constraints' => [
                    new NotBlank(['message' => "Ingrese su nombre"])
                ]
            ])
            ->add('from', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [
                    new NotBlank(['message' => "Ingrese su correo"]),
                    new Email(['message' => "Ingrese un email valido"])
                ]
            ])
            ->add('mensaje', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => "Ingrese mensaje"])
                ]
            ]);
    }
}
