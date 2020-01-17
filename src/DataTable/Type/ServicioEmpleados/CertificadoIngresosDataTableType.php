<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\Nomina;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class CertificadoIngresosDataTableType implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $id = $options['id'];

        $dataTable
            ->add('periodo', DateTimeColumn::class, ['label' => 'Periodo', 'format' => 'Y'])
            ->add('actions', ActionsColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'field' => 'usuario.identificacion',
                'actions' => [
                    'route' => ['se_certificado_ingresos_pdf', ['certificado' => 'id']],
                    'icon' => 'fas fa-file-pdf',
                    'target' => '_blank'
                ]
            ])
            ->addOrderBy('periodo', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => CertificadoIngresos::class,
                'query' => function (QueryBuilder $builder) use ($id) {
                    $builder
                        ->select('ci')
                        ->from(CertificadoIngresos::class, 'ci')
                        ->join('ci.usuario', 'usuario')
                        ->where('usuario.id = :id')
                        ->setParameter('id', $id);
                },
            ]);
        $dataTable->setName('certificado-ingresos');
    }
}