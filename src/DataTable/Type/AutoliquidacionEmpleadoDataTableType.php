<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Autoliquidacion\AutoliquidacionEmpleado;
use App\Repository\Main\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;

class AutoliquidacionEmpleadoDataTableType implements DataTableTypeInterface
{

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $id = $options['id'] ?? null;
        $convenio = $options['convenio'] ?? null;
        $periodo = $options['periodo'] ?? null;
        $empleado = $options['empleado'] ?? null;

        if($convenio) {
            $dataTable
                ->add('identificacion', TextColumn::class, ['label' => 'Identificación', 'field' => 'u.identificacion'])
                ->add('nombreCompleto', TextColumn::class, ['label' => 'Nombres', 'field' => 'u.nombreCompleto', 'orderable' => false])
                ->add('exito', TextColumn::class, ['label' => 'Exito', 'field' => 'ae.exito', 'orderable' => false])
                ->addOrderBy('identificacion', DataTable::SORT_DESCENDING);
        }
        if(!$periodo) {
            $dataTable->add('periodo', DateTimeColumn::class, ['label' => 'Fecha', 'format' => 'Y-m', 'field' => 'a.periodo'])
                ->addOrderBy('periodo', DataTable::SORT_ASCENDING);
        }
        $dataTable
            ->add('PDF', ActionsColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'field' => 'ae.id',
                'actions' => [
                    'route' => ['app_certificado_aporte', ['id' => 'id']],
                    'icon' => 'fas fa-file-pdf',
                    'target' => '_blank',
                    'tooltip' => 'Ver',
                    'data' => function (AutoliquidacionEmpleado $autoliquidacionEmpleado, $id) {
                        return $autoliquidacionEmpleado->isExito() ? $id : 'disabled';
                    },
                ]
            ])

            ->createAdapter(ORMAdapter::class, [
                'entity' => AutoliquidacionEmpleado::class,
                'query' => function (QueryBuilder $builder) use ($id, $convenio, $periodo, $empleado) {
                    $builder
                        ->select('ae, a')
                        ->from(AutoliquidacionEmpleado::class, 'ae')
                        ->join('ae.autoliquidacion', 'a')
                        ->join('ae.empleado', 'e')
                        ->join('e.usuario', 'u');
                        //->andWhere('ae.exito = 1');
                    if($id){
                        $builder->andWhere('u.id = :id')
                            ->setParameter('id', $id);
                    }
                    else if($convenio && $periodo) {
                        $builder->andWhere($builder->expr()->andX(
                            $builder->expr()->eq('a.convenio', ':convenio'),
                            $builder->expr()->eq('a.periodo', ':periodo')
                        ))
                            ->setParameter('convenio', $convenio)
                            ->setParameter('periodo', $periodo->format('Y-m-d'));
                    }
                    else if($empleado) {
                        $builder->andWhere('ae.empleado = :empleado')
                            ->setParameter('empleado', $empleado);
                    }
                },
                'criteria' => [
                    function(QueryBuilder $builder, DataTableState $state) {
                        if($state->getGlobalSearch()) {
                            $builder->andWhere(
                                $this->usuarioRepository->userSearchExpression($builder, $state->getGlobalSearch())
                            );
                        }
                    },
                ]
            ])
        ;
        $dataTable->setName('autoliquidacion-empleado');
    }
}