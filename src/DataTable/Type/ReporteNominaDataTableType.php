<?php


namespace App\DataTable\Type;


use App\Entity\ReporteNomina;
use App\Entity\Usuario;
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
            ->add('id', TextColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'render' => function ($id) {
                    $route = $this->router->generate('app_comprobante', ['comprobante' => $id]);
                    return sprintf('<a href="%s" target="_blank"><i class="fas fa-file-pdf fa-2x"></i></a>', $route);
                }
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