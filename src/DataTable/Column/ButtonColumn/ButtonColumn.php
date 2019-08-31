<?php

/*
 * Symfony DataTables Bundle
 * (c) Omines Internetbureau B.V. - https://omines.nl/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace App\DataTable\Column\ButtonColumn;

use Omines\DataTablesBundle\Column\AbstractColumn;
use Omines\DataTablesBundle\DataTable;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * @deprecated
 */
class ButtonColumn extends AbstractColumn
{

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(string $name, int $index, array $options = [], DataTable $dataTable)
    {
        parent::initialize($name, $index, $options, $dataTable);
        foreach($options['buttons'] as $button) {
            $button->setColumn($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function render($value, $context)
    {
        $html = "";
        /** @var ButtonType $button */
        foreach($this->options['buttons'] as $button) {
            $html .= $button->render($value, $context);
        }
        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value): string
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
            ->setRequired('buttons')
            ->setAllowedTypes('buttons', 'array')
        ;

        $resolver->setDefaults(['className' => 'actions']);

        return $this;
    }

}
