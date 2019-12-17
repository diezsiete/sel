<?php


namespace App\DataTable\Column;


use Omines\DataTablesBundle\Column\AbstractColumn;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFormatColumn extends AbstractColumn
{

    /**
     * The normalize function is responsible for converting parsed and processed data to a datatables-appropriate type.
     *
     * @param mixed $value The single value of the column
     * @return mixed
     */
    public function normalize($value)
    {
        return number_format($value, 0);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'decimals' => 0,
            'dec_point' => '.',
            'thousands_step' => ','
        ]);

        $resolver
            ->setAllowedTypes('decimals', 'int')
            ->setAllowedTypes('dec_point', 'string')
            ->setAllowedTypes('thousands_step', 'string')
        ;

        return $this;
    }
}