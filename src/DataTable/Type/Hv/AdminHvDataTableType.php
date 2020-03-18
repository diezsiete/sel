<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\DataTable\Adapter\GroupByORMAdapter;
use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Hv\Hv;
use App\Entity\Vacante\Vacante;
use App\Repository\Hv\HvRepository;
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
     * @var \App\Repository\Hv\HvRepository
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
            ->add('nivelAcademico', TextColumn::class, [
                'label' => 'Nivel academico',
                'field' => 'na.nombre',
                'orderable' => false])
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
                    ],
                    [
                        'icon' => 'fas fa-upload',
                        'tooltip' => 'Cargar a novasoft',
                        'data-id' => 'hv.id',
                        'class' => 'scraper'
                    ],
                ]
            ])
            ->createAdapter(GroupByORMAdapter::class, [
                'entity' => Hv::class,
                'query' => function (QueryBuilder $builder) use ($vacante) {
                    $builder
                        ->select('hv')
                        ->from(Hv::class, 'hv')
                        ->join('hv.nivelAcademico', 'na')
                        ->where('usuario is not null');

                    $this->hvRepository->searchQueryBuilderFields($builder);

                    if($vacante) {
                        $builder->join('hv.vacantes', 'v')
                            ->where('v = :vacante')
                            ->setParameter('vacante', $vacante);
                    }

                    return $builder;
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