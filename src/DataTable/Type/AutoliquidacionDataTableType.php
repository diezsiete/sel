<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Security\Core\Security;

class AutoliquidacionDataTableType implements DataTableTypeInterface
{


    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(AutoliquidacionRepository $autoliquidacionRepository, Security $security)
    {
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->security = $security;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('id', TextColumn::class, ['label' => 'id'])
            ->add('periodo', DateTimeColumn::class,
                ['label' => 'Periodo', 'format' => 'Y-m'])
            ->add('codigo', TextColumn::class,
                ['label' => 'Convenio codigo', 'field' => 'convenio.codigo'])
            ->add('nombre', TextColumn::class,[
                'label' => 'Convenio nombre', 'field' => 'convenio.nombre'
            ])
            ->add('porcentajeEjecucion', NumberColumn::class, ['label' => '%', 'orderable' => false])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'a.id',
                'orderable' => false,
                'actions' => [
                    [
                        'route' => ['admin_autoliquidacion_export', ['id' => 'id', 'type' => '"pdf"']],
                        'icon' => 'fas fa-file-pdf',
                        'tooltip' => 'PDF Unificado'
                    ],
                    [
                        'route' => ['admin_autoliquidacion_export', ['id' => 'id', 'type' => '"zip"']],
                        'icon' => 'far fa-file-archive',
                        'tooltip' => 'Comprimido Zip'
                    ],
                    [
                        'route' => ['admin_autoliquidacion_detalle', ['codigo' => 'convenio.codigo', 'periodo' => 'periodoFormat']],
                        'icon' => 'fas fa-eye',
                        'tooltip' => 'Ver'
                    ]
                ]
            ])
            ->addOrderBy('id', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Autoliquidacion::class,
                'query' => function (QueryBuilder $builder){
                    $builder
                        ->select('a, convenio')
                        ->from(Autoliquidacion::class, 'a')
                        ->join('a.convenio', 'convenio');
                },
            ])
        ;
    }
}