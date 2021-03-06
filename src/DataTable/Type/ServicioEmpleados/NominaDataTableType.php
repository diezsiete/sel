<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\ServicioEmpleados\Nomina;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class NominaDataTableType extends ServicioEmpleadosDataTableType
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('fecha', DateTimeColumn::class, ['label' => 'Fecha', 'format' => 'Y-m-d'])
            ->add('convenio', TextColumn::class, ['label' => 'Convenio', 'orderable' => false])
            ->add('identificacion', TextColumn::class, [
                'label' => 'Identificación',
                'field' => 'usuario.identificacion',
                'orderable' => false
            ])
            ->add('actions', ActionsColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'field' => 'usuario.identificacion',
                'actions' => [
                    'route' => ['se_comprobante', ['nomina' => 'id']],
                    'icon' => 'fas fa-file-pdf',
                    'target' => '_blank'
                ]
            ])
            ->addOrderBy('fecha', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Nomina::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('n')
                        ->from(Nomina::class, 'n')
                        ->join('n.usuario', 'usuario')
                        ->where('usuario = :usuario')
                        ->setParameter('usuario', $this->usuario);
                },
            ]);
        $dataTable->setName('reporte-nomina');
    }

    protected function getReportEntityClass(): string
    {
        return Nomina::class;
    }
}