<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Experiencia;
use App\Entity\Familiar;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class FamiliarDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $hv = $options['hv'];

        $dataTable
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('parentesco', TextColumn::class, ['label' => 'Parentesco', 'render' => function($val) {
                return HvConstant::PARENTESCO[$val];
            }])
            ->add('ocupacion', TextColumn::class, ['label' => 'Ocupación', 'render' => function($val) {
                return HvConstant::OCUPACION[$val];
            } ])
            ->add('nivelAcademico', TextColumn::class, ['label' => 'Nivel académimco', 'render' => function($val) {
                return HvConstant::NIVEL_ACADEMICO[$val];
            } ])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'familiar']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'familiar'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'familiar'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Familiar::class,
                'query' => function (QueryBuilder $builder) use ($hv) {
                    $builder
                        ->select('f')
                        ->from(Familiar::class, 'f')
                        ->where('f.hv = :hv')
                        ->setParameter('hv', $hv);
                },
            ]);
    }
}