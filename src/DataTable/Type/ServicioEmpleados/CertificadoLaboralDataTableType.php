<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\ServicioEmpleados\CertificadoLaboral;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class CertificadoLaboralDataTableType implements DataTableTypeInterface
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $id = $options['id'];

        $dataTable
            ->add('fechaIngreso', DateTimeColumn::class, ['label' => 'Fecha ingreso', 'format' => 'Y-m-d'])
            ->add('fechaRetiro', DateTimeColumn::class, ['label' => 'Fecha retiro', 'format' => 'Y-m-d'])
            ->add('identificacion', TextColumn::class, [
                'label' => 'Identificación',
                'field' => 'usuario.identificacion',
                'orderable' => false
            ])
            ->add('actions', ActionsColumn::class, [
                'label' => 'PDF',
                'orderable' => false,
                'field' => 'cl.id',
                'actions' => [
                    'route' => ['se_certificado_laboral_pdf', ['certificado' => 'id']],
                    'icon' => 'fas fa-file-pdf',
                    'target' => '_blank'
                ]
            ])
            ->addOrderBy('fechaIngreso', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => CertificadoLaboral::class,
                'query' => function (QueryBuilder $builder) use ($id) {
                    $builder
                        ->select('cl')
                        ->from(CertificadoLaboral::class, 'cl')
                        ->join('cl.usuario', 'usuario')
                        ->where('usuario.id = :id')
                        ->setParameter('id', $id);
                },
            ]);
        $dataTable->setName('certificado-laboral');
    }
}