<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Hv\Experiencia;
use App\Entity\Hv\Familiar;
use App\Entity\Hv\Idioma;
use App\Entity\Hv\RedSocial;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class RedSocialDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $hv = $options['hv'];

        $dataTable
            ->add('tipo', TextColumn::class, ['label' => 'Red social', 'render' => function($id) {
                return HvConstant::RED_SOCIAL[$id];
            } ])
            ->add('cuenta', TextColumn::class, ['label' => 'Cuenta'])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'red_social']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'red_social'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'red_social'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => RedSocial::class,
                'query' => function (QueryBuilder $builder) use ($hv) {
                    $builder
                        ->select('rs')
                        ->from(RedSocial::class, 'rs')
                        ->where('rs.hv = :hv')
                        ->setParameter('hv', $hv);
                },
            ]);
    }
}