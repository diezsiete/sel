<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeRoute;
use App\DataTable\Column\ButtonColumn\DatatablePropertyAccessor;
use App\Entity\Evaluacion\Progreso;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;

class EvaluacionProgresoDataTableType implements DataTableTypeInterface
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
        $usuario = $options['usuario'] ?? null;

        $actions = [[
            'route' => ['evaluacion_certificado', [
                'evaluacionSlug' => 'evaluacion.slug',
                'progresoId' => 'id'
            ]],
            'icon' => 'fas fa-file-pdf',
            'target' => '_blank',
            'data' => function (Progreso $progreso, $id) {
                return $progreso->getCulminacion() ? $id : 'disabled';
            },
            'tooltip' => 'Certificado'
        ]];

        if($usuario) {
            $actions[] = [
                'route' => ['evaluacion', [
                    'evaluacionSlug' => 'evaluacion.slug',
                ]],
                'icon' => 'fas fa-pencil-alt',
                'data' => function (Progreso $progreso, $id) {
                    return $progreso->getCulminacion() ? false : $id;
                },
                'tooltip' => 'Culminar'
            ];
        }

        $dataTable
            ->add('nombre', TextColumn::class, ['label' => 'Evaluacion', 'field' => 'evaluacion.nombre'])
            // ->add('descripción', TextColumn::class, ['label' => 'Descripción', 'field' => 'evaluacion.descripcion'])
            ->add('culminacion', DateTimeColumn::class, ['label' => 'Culminación', 'format' => 'Y-m-d'])
            ->add('identificacion', TextColumn::class, [
                'label' => 'Identificación',
                'field' => 'usuario.identificacion',
            ])
            ->add('usuario', TextColumn::class, [
                'label' => 'Usuario',
                'field' => 'usuario.nombreCompleto'
            ])
            ->add('porcentajeCompletitud', TextColumn::class, ['label' => 'Completitud %', 'orderable' => false])
            ->add('porcentajeExito', TextColumn::class, ['label' => 'Exito %', 'orderable' => false])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'progreso.id',
                'orderable' => false,
                'actions' => $actions
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Progreso::class,
                'query' => function (QueryBuilder $builder) use ($usuario) {
                    $builder
                        ->select('progreso, evaluacion, usuario')
                        ->from(Progreso::class, 'progreso')
                        ->join('progreso.evaluacion', 'evaluacion')
                        ->join('progreso.usuario', 'usuario');
                    if($usuario){
                        $builder->andWhere('usuario = :usuario')->setParameter('usuario', $usuario);
                    }
                },
                'criteria' => [
                    function(QueryBuilder $builder, DataTableState $state) {
                        if($state->getGlobalSearch()) {
                            $builder->andWhere(
                                $this->usuarioRepository->userSearchExpression($builder, $state->getGlobalSearch(), 'usuario')
                            );
                        }
                    },
                ]
            ])
            ->addOrderBy('culminacion', DataTable::SORT_DESCENDING)
        ;
    }
}