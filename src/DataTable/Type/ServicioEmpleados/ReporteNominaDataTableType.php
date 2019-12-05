<?php


namespace App\DataTable\Type\ServicioEmpleados;


use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ReporteNominaDataTableType implements DataTableTypeInterface
{

    public function configure(DataTable $dataTable, array $options)
    {
        /*$nominaReport = $reportFactory->getReporteNomina($this->getUser()->getIdentificacion());
        dump($nominaReport->renderMap());*/
        $dataTable
            ->add('firstName', TextColumn::class)
            ->add('lastName', TextColumn::class)
            ->add('lastActivity', DateTimeColumn::class, [
                'data' => function () {
                    return '2017-1-1 12:34:56';
                },
                'format' => 'd-m-Y',
            ])
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
                ['firstName' => 'George W.', 'lastName' => 'Bush'],
                ['firstName' => 'Bill', 'lastName' => 'Clinton'],
                ['firstName' => 'George H.W.', 'lastName' => 'Bush'],
                ['firstName' => 'Ronald', 'lastName' => 'Reagan'],
            ])
            ->setTransformer(function ($row) {
                $row['lastName'] = mb_strtoupper($row['lastName']);

                return $row;
            })
        ;
    }
}