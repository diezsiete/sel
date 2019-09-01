<?php /** @noinspection PhpOptionalBeforeRequiredParametersInspection */

namespace App\DataTable\Column\ActionsColumn;

use Omines\DataTablesBundle\Column\AbstractColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DependencyInjection\Instantiator;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 *
 */
class ActionsColumn extends AbstractColumn
{

    /**
     * @var ContainerInterface
     */
    private $locator;

    /**
     * @var Action[]
     */
    private $actions = [];

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $name, int $index, array $options = [], DataTable $dataTable)
    {
        parent::initialize($name, $index, $options, $dataTable);

        if(!is_int(array_key_first($options['actions']))) {
            $options['actions'] = [$options['actions']];
        }
        foreach($options['actions'] as $action) {
            if(isset($action['route'])) {
                if ($this->locator->has(ActionRoute::class)) {
                    $actionObject = $this->locator->get(ActionRoute::class);
                    $actionObject->setOptions($action);
                    $this->actions[] = $actionObject;
                }
            }
        }
    }

    public function transform($value = null, $context = null)
    {
        $data = $this->getData();
        if (is_callable($data)) {
            $value = call_user_func($data, $context, $value);
        } elseif (null === $value) {
            $value = $data;
        }
        $actionsAttributes = [];
        if($value) {
            foreach($this->actions as $action) {
                if($attributes = $action->transform($value, $context)){
                    $actionsAttributes[] = $attributes;
                }
            }
        }

        return $this->render($this->normalize($actionsAttributes), $context);
    }

    /**
     * {@inheritdoc}
     */
    public function render($actionsAttributes, $context): string
    {
        $html = "";
        foreach($actionsAttributes as $actionAttributes) {
            $tag = "a";
            $actionAttributes['class'] = "btn btn-outline-primary g-mr-10";
            if(isset($actionAttributes['disabled'])) {
                $tag = "span";
                $actionAttributes['class'] = (isset($actionAttributes['class']) ? $actionAttributes['class'] . ' ' : '') . 'disabled';
            }
            $buttonText = "undefined";
            if(isset($actionAttributes['icon'])) {
                $buttonText = "<i class='{$actionAttributes['icon']}'></i>";
            }
            $attributes = $this->parseAttributes($actionAttributes);
            $html .= sprintf('<%s %s>%s</%s>',$tag, $attributes, $buttonText, $tag);
        }
        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value)
    {
        return $value;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired('actions')
            ->setAllowedTypes('actions', 'array')
        ;

        $resolver->setDefaults(['className' => 'actions']);

        return $this;
    }

    private function parseAttributes($attributes)
    {
        $parse = " ";
        foreach($attributes as $attributeName => $attributeValue) {
            if($attributeName !== 'icon' && $attributeName !== 'disabled') {
                $parse .= $attributeName . '="' . $attributeValue . '" ';
            }
        }
        return $parse;
    }
}
