<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeRoute;
use App\DataTable\Column\ButtonColumn\DatatablePropertyAccessor;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class AutoliquidacionEmpleadoDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $id = $options['id'];

        $dataTable
            ->add('periodo', DateTimeColumn::class, ['label' => 'Fecha', 'format' => 'Y-m', 'field' => 'a.periodo'])
            ->add('PDF', ButtonColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'field' => 'ae.id',
                'buttons' => [
                    new ButtonTypeRoute('app_certificado_aporte', [
                        'id' => new DatatablePropertyAccessor('id'),
                    ], 'fas fa-file-pdf', '_blank'),
                ],
            ])

            ->addOrderBy('periodo', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => AutoliquidacionEmpleado::class,
                'query' => function (QueryBuilder $builder) use ($id) {
                    $builder
                        ->select('ae, a')
                        ->from(AutoliquidacionEmpleado::class, 'ae')
                        ->join('ae.autoliquidacion', 'a')
                        ->join('ae.empleado', 'e')
                        ->join('e.usuario', 'u')
                        ->where('u.id = :id')
                        ->andWhere('ae.exito = 1')
                        ->setParameter('id', $id);
                },
            ])
        ;
        $dataTable->setName('autoliquidacion-empleado');
    }
}