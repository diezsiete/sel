<?php


namespace App\DataTable\Type\PortalClientes;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\NumberFormatColumn;
use App\Entity\Novasoft\Report\TrabajadorActivo;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class TrabajadoresActivosDataTableType implements DataTableTypeInterface
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
            ->add('identificacion', TextColumn::class, [
                'label' => 'Identificación',
                'field' => 'u.identificacion',
                'orderable' => false
            ])
            ->add('nombrePrimeros', TextColumn::class, [
                'label' => 'nombres',
                'field' => 'u.nombreCompleto'
            ])
            ->add('fechaIngreso', DateTimeColumn::class, [
                'label' => 'F. Ingreso',
                'format' => 'Y-m-d',
                'field' => 'ta.fechaIngreso'
            ])
            ->add('fechaRetiro', DateTimeColumn::class, [
                'label' => 'F. Retiro',
                'format' => 'Y-m-d',
                'field' => 'ta.fechaRetiro'
            ])
            ->add('ingresoBasico', NumberFormatColumn::class, [
                'label' => 'Ingreso basico',
                'field' => 'ta.ingresoBasico'
            ])
            ->add('labor', TextColumn::class, [
                'label' => 'Labor',
                'field' => 'ta.labor'
            ])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'e.id',
                'orderable' => false,
                'actions' => [
                    'route' => ['clientes_trabajador_activo_detalle', ['id' => 'id']],
                    'icon' => 'fas fa-eye',
                    'tooltip' => 'Ver empleado'
                ]
            ])
            ->addOrderBy('fechaIngreso', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => TrabajadorActivo::class,
                'query' => function (QueryBuilder $builder) use ($convenioFilter){
                    $builder
                        ->select('ta, e, u')
                        ->from(TrabajadorActivo::class, 'ta')
                        ->join('ta.empleado', 'e')
                        ->join('e.usuario', 'u');
                    if($convenioFilter) {
                        $builder
                            ->join('ta.convenio', 'c')
                            ->addSelect('c')
                            ->andWhere('ta.convenio = :convenio')
                            ->setParameter('convenio', $convenioFilter);
                    }
                },
            ])
        ;

    }
}