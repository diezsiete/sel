<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\DataTable\Adapter\GroupByORMAdapter;
use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Hv;
use App\Entity\Vacante;
use App\Repository\HvRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Bundle\TimeBundle\Twig\Extension\TimeExtension;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Twig\Environment;

class AdminHvDataTableType implements DataTableTypeInterface
{
    /**
     * @var HvRepository
     */
    private $hvRepository;

    /**
     * @var TimeExtension
     */
    private $timeExtension;

    public function __construct(HvRepository $hvRepository, Environment $twig)
    {
        $this->hvRepository = $hvRepository;
        $this->timeExtension = $twig->getExtension(TimeExtension::class);

    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $vacante = $options['vacante'] ?? null;
        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'Identificación', 'field' => 'usuario.identificacion'])
            ->add('nombreCompleto', TextColumn::class, ['label' => 'Nombre', 'field' => 'usuario.nombrePrimeros'])
            ->add('registro', DateTimeColumn::class, ['label' => 'Registro', 'field' => 'usuario.createdAt', 'format' => 'Y-m-d'])
            ->add('residencia', TextColumn::class, ['label' => 'Residencia', 'field' => 'resiCiudad.nombre'])
            ->add('edad', DateTimeColumn::class, ['label' => 'Edad', 'field' => 'hv.nacimiento', 'format' => 'Y-m-d', 'render' => function($value, $context) {
                return $this->timeExtension->diff($value);
            }])
            ->add('nivelAcademico', MapColumn::class, ['label' => 'Nivel academico', 'field' => 'hv.nivelAcademico',
                'orderable' => false, 'map' => HvConstant::NIVEL_ACADEMICO])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'hv.id',
                'orderable' => false,
                'actions' => [
                    [
                        'route' => ['admin_hv_detalle', ['id' => 'hv.id']],
                        'icon' => 'fas fa-eye',
                        'tooltip' => 'Ver',
                        'target' => '_blank'
                    ]/*,
                    [
                        'route' => ['admin_autoliquidacion_export', ['id' => 'id', 'type' => '"zip"']],
                        'icon' => 'far fa-file-archive',
                        'tooltip' => 'Comprimido Zip'
                    ],*/
                ]
            ])
            ->createAdapter(GroupByORMAdapter::class, [
                'entity' => !$vacante ? Hv::class : Vacante::class,
                'query' => function (QueryBuilder $builder) use ($vacante) {
                    $builder
                        ->select('hv')
                        ->from(Hv::class, 'hv')
                        ->where('usuario is not null');

                    $this->hvRepository->searchQueryBuilderFields($builder);

                    if($vacante) {
                        $builder->join(Vacante::class, 'v', 'WITH', 'usuario.id = v.usuario')
                            ->where('v = :vacante')
                            ->setParameter('vacante', $vacante);
                    }
                },
                'criteria' => [
                    function(QueryBuilder $builder, DataTableState $state) {
                        if($state->getGlobalSearch()) {
                            $this->hvRepository->searchExpression($builder, $state->getGlobalSearch());
                        }
                    },
                ]
            ]);
    }
}