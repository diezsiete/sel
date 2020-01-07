<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Hv\Experiencia;
use App\Entity\Hv\Familiar;
use App\Entity\Hv\Vivienda;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ViviendaDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $hv = $options['hv'];

        $dataTable
            ->add('tipoVivienda', TextColumn::class, ['label' => 'Tipo de vivienda', 'render' => function($val) {
                return HvConstant::VIVIENDA_TIPO[$val];
            }])
            ->add('direccion', TextColumn::class, ['label' => 'Dirección'])
            ->add('ciudad', TextColumn::class, ['label' => 'Ciudad', 'field' => 'ciudad.nombre'])
            ->add('viviendaActual', TextColumn::class, ['label' => 'Vivienda actual', 'render' => function($val) {
                return $val ? "SI" : "NO";
            } ])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'vivienda']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'vivienda'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'vivienda'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Vivienda::class,
                'query' => function (QueryBuilder $builder) use ($hv) {
                    $builder
                        ->select('v')
                        ->from(Vivienda::class, 'v')
                        ->join('v.ciudad', 'ciudad')
                        ->where('v.hv = :hv')
                        ->setParameter('hv', $hv);
                },
            ]);
    }
}