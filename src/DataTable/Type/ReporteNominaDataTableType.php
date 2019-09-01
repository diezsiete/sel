<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\ReporteNomina;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class ReporteNominaDataTableType implements DataTableTypeInterface
{

    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $id = $options['id'];

        $dataTable
            ->add('fecha', DateTimeColumn::class, ['label' => 'Fecha', 'format' => 'Y-m-d'])
            ->add('convenioCodigoNombre', TextColumn::class, ['label' => 'Convenio', 'orderable' => false])
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
                    'route' => ['app_comprobante', ['comprobante' => 'id']],
                    'icon' => 'fas fa-file-pdf',
                    'target' => '_blank'
                ]
            ])
            ->addOrderBy('fecha', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => ReporteNomina::class,
                'query' => function (QueryBuilder $builder) use ($id) {
                    $builder
                        ->select('rn')
                        ->from(ReporteNomina::class, 'rn')
                        ->join('rn.usuario', 'usuario')
                        ->where('usuario.id = :id')
                        ->setParameter('id', $id);
                },
            ]);
        $dataTable->setName('reporte-nomina');
    }
}