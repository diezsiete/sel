<?php


namespace App\DataTable\Type\Clientes;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\NumberFormatColumn;
use App\Entity\Novasoft\Report\Clientes\ListadoNomina\ListadoNomina;
use App\Entity\Novasoft\Report\LiquidacionNomina\LiquidacionNominaResumen;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ListadoNominaResumenDataTableType implements DataTableTypeInterface
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
            ->add('fechaNomina', DateTimeColumn::class, [
                'label' => 'Fecha',
                'format' => 'Y-m-d'
            ])
            ->add('tipoLiquidacion', MapColumn::class, [
                'label' => 'Tipo liquidación',
                'map' => ListadoNomina::TIPO_LIQUIDACION
            ])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'ln.id',
                'orderable' => false,
                'actions' => [
                    'route' => ['clientes_listado_nomina_detalle', ['id' => 'id']],
                    'icon' => 'fas fa-eye',
                    'tooltip' => 'Ver detalle'
                ]
            ])
            ->addOrderBy('fechaNomina', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => ListadoNomina::class,
                'query' => function (QueryBuilder $builder) use ($convenioFilter){
                    $builder
                        ->select('ln, c')
                        ->from(ListadoNomina::class, 'ln')
                        ->join('ln.convenio', 'c');
                    if($convenioFilter) {
                        $builder
                            ->andWhere('ln.convenio = :convenio')
                            ->setParameter('convenio', $convenioFilter);
                    }
                },
            ])
        ;
    }
}