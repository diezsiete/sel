<?php


namespace App\DataTable\Type;


use App\DataTable\Adapter\GroupByORMAdapter;
use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\CheckboxColumn;
use App\Entity\Convenio;
use App\Entity\Representante;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ConvenioDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('codigo', TextColumn::class, ['label' => 'Codigo', 'propertyPath' => '[0].codigo'])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre', 'propertyPath' => '[0].nombre'])
            ->add('direccion', TextColumn::class, [
                'label' => 'Direccion',
                'propertyPath' => '[0].direccion',
                'orderable' => false
            ])
            ->add('empleadosCount', TextColumn::class, [
                'label' => 'Empleados',
                'propertyPath' => '[empleadosCount]',
                // 'orderable' => true,
            ])
            ->add('representantes', TextColumn::class, [
                'label' => 'Representante',
                'propertyPath' => '[0]',
                'raw' => true,
                'data' => function ($row, Convenio $convenio) {
                    $data = "";
                    foreach($convenio->getRepresentantes(Representante::TYPE_CLIENTE) as $representante) {
                        $data .= "<p>" . $representante->getUsuario()->getNombreCompleto(true) . "</p>";
                    }
                    return $data;
                }
            ])
            ->add('servicio', TextColumn::class, [
                'label' => 'Servicio',
                'propertyPath' => '[0]',
                'raw' => true,
                'data' => function ($row, Convenio $convenio) {
                    $data = "";
                    foreach($convenio->getRepresentantes(Representante::TYPE_SERVICIO) as $representante) {
                        $data .= "<p>" . $representante->getUsuario()->getNombreCompleto(true) . "</p>";
                    }
                    return $data;
                }])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'propertyPath' => '[0].codigo',
                'orderable' => false,
                'actions' => [
                    'route' => ['admin_convenio_representantes', ['codigo' => '[0].codigo']],
                    'icon' => 'fas fa-eye'
                ]
            ])
            ->createAdapter(GroupByORMAdapter::class, [
                'entity' => Convenio::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('c')
                        ->from(Convenio::class, 'c')
                        ->addSelect('COUNT(e) AS empleadosCount')
                        ->join('c.empleados', 'e')
                        ->leftJoin('c.representantes', 'r')
                        ->leftJoin('r.usuario', 'u')
                        ->groupBy('c.codigo')
                    ;
                },
                'criteria' => function (QueryBuilder $qb, DataTableState $state) {
                    if($state->getGlobalSearch()) {
                        $qb->andWhere($qb->expr()->orX(
                            $qb->expr()->like('c.codigo', ':search'),
                            $qb->expr()->like('c.nombre', ':search'),
                            $qb->expr()->like('u.primerNombre', ':search')
                        ))->setParameter('search', "%".$state->getGlobalSearch()."%");
                    }
                },
            ])
            ->addOrderBy('codigo', DataTable::SORT_ASCENDING)
        ;

        if(isset($options['form'])) {
            $dataTable->add('datatable', CheckboxColumn::class, ['label'=> '', 'propertyPath' => '[0].codigo']);
        }
    }
}