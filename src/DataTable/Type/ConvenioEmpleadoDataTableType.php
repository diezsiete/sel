<?php


namespace App\DataTable\Type;


use App\DataTable\Adapter\GroupByORMAdapter;
use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\DataTable\Column\CheckboxColumn;
use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Entity\Main\Representante;
use App\Repository\Main\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ConvenioEmpleadoDataTableType implements DataTableTypeInterface
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;

    public function __construct(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $convenio = $options['convenio'];
        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'Identificación', 'field' => 'u.identificacion'])
            ->add('nombreCompleto', TextColumn::class, ['label' => 'Nombre', 'field' => 'u.nombreCompleto'])
            ->add('fechaIngreso', DateTimeColumn::class, ['label' => 'Ingreso', 'format' => 'Y-m-d'])
            ->add('fechaRetiro', DateTimeColumn::class, ['label' => 'Retiro', 'format' => 'Y-m-d'])
            ->addOrderBy('identificacion', DataTable::SORT_ASCENDING)
            //->addColumn(['label' => 'Representante', 'data' => 'repAsignado'])
            //->addColumnCheckbox(['data' => 'id', 'selectAllCheckbox' => true, 'form' => $form, "name" => "empleados"])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Empleado::class,
                'query' => function (QueryBuilder $builder) use ($convenio) {
                    $builder
                        ->select('e, u')
                        ->from(Empleado::class, 'e')
                        ->join('e.usuario', 'u')
                        ->where('e.convenio = :convenio')
                        ->setParameter('convenio', $convenio)
                    ;
                },
                'criteria' => function (QueryBuilder $qb, DataTableState $state) {
                    if($state->getGlobalSearch()) {
                        $qb->andWhere(
                            $this->usuarioRepo->userSearchExpression($qb, $state->getGlobalSearch())
                        );
                    }
                },
            ])
        ;
    }
}