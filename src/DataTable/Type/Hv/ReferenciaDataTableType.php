<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Experiencia;
use App\Entity\Referencia;
use App\Service\Configuracion\Configuracion;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ReferenciaDataTableType implements DataTableTypeInterface
{

    /**
     * @var Configuracion
     */
    private $configuracion;

    public function __construct(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $hv = $options['hv'];

        $dataTable
            ->add('tipo', TextColumn::class, ['label' => 'Tipo de referencia', 'render' => function($id) {
                return $this->configuracion->getHvReferenciaTipo(false)[$id];
            }])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('ocupacion', TextColumn::class, ['label' => 'Ocupación'])
            ->add('parentesco', TextColumn::class, ['label' => 'Parentesco'])
            ->add('celular', TextColumn::class, ['label' => 'Celular'])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'referencia']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'referencia'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'referencia'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Referencia::class,
                'query' => function (QueryBuilder $builder) use ($hv) {
                    $builder
                        ->select('r')
                        ->from(Referencia::class, 'r')
                        ->where('r.hv = :hv')
                        ->setParameter('hv', $hv);
                },
            ]);
    }
}