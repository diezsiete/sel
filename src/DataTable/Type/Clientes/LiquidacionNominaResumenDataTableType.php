<?php


namespace App\DataTable\Type\Clientes;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\NumberFormatColumn;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class LiquidacionNominaResumenDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $convenioFilter = $options['convenio'] ?? null;

        if(!$convenioFilter) {
            $dataTable->add('convenio', TextColumn::class, ['label' => 'Convenio', 'field' => 'c.codigo']);
        }

        $dataTable
            ->add('fechaInicial', DateTimeColumn::class, [
                'label' => 'Fecha Inicial',
                'format' => 'Y-m-d'
            ])
            ->add('fechaFinal', DateTimeColumn::class, [
                'label' => 'Fecha final',
                'format' => 'Y-m-d',
            ])
            ->add('neto', NumberFormatColumn::class, [
                'label' => 'Neto',
                'field' => 't.neto'
            ])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'lnr.id',
                'orderable' => false,
                'actions' => [
                    'route' => ['clientes_liquidacion_nomina_detalle', ['id' => 'id']],
                    'icon' => 'fas fa-eye',
                    'tooltip' => 'Ver detalle'
                ]
            ])
            ->addOrderBy('fechaInicial', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => LiquidacionNominaResumen::class,
                'query' => function (QueryBuilder $builder) use ($convenioFilter){
                    $builder
                        ->select('lnr')
                        ->from(LiquidacionNominaResumen::class, 'lnr')
                        ->join('lnr.convenio', 'c')
                        ->join('lnr.total', 't');
                    if($convenioFilter) {
                        $builder
                            ->addSelect('c')
                            ->andWhere('lnr.convenio = :convenio')
                            ->setParameter('convenio', $convenioFilter);
                    }
                },
            ])
        ;
    }
}