<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\ServicioEmpleados\CertificadoIngresos;
use App\Entity\ServicioEmpleados\LiquidacionContrato;
use App\Entity\ServicioEmpleados\Nomina;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class LiquidacionContratoDataTableType extends ServicioEmpleadosDataTableType
{
    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('fechaIngreso', DateTimeColumn::class, ['label' => 'Fecha ingreso', 'format' => 'Y-m-d'])
            ->add('fechaRetiro', DateTimeColumn::class, ['label' => 'Fecha retiro', 'format' => 'Y-m-d'])
            ->add('convenio', TextColumn::class, ['label' => 'Convenio'])
            ->add('contrato', TextColumn::class, ['label' => 'Contrato'])
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
            ->addOrderBy('fechaIngreso', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => LiquidacionContrato::class,
                'query' => function (QueryBuilder $builder){
                    $builder
                        ->select('lc')
                        ->from(LiquidacionContrato::class, 'lc')
                        ->join('lc.usuario', 'usuario')
                        ->where('usuario = :usuario')
                        ->setParameter('usuario', $this->usuario);
                },
            ]);
        $dataTable->setName('liquidacion-contrato');
    }

    protected function getReportEntityClass(): string
    {
        return LiquidacionContrato::class;
    }
}