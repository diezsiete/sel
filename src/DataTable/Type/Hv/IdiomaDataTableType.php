<?php


namespace App\DataTable\Type\Hv;


use App\Constant\HvConstant;
use App\Constant\VacanteConstant;
use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Experiencia;
use App\Entity\Familiar;
use App\Entity\Idioma;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class IdiomaDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $usuario = $options['usuario'];

        $dataTable
            ->add('idiomaCodigo', TextColumn::class, ['label' => 'Idioma', 'render' => function($id) {
                return VacanteConstant::IDIOMA_CODIGO[$id];
            } ])
            ->add('destreza', TextColumn::class, ['label' => 'Destreza', 'render' => function($id) {
                return VacanteConstant::IDIOMA_DESTREZA[$id];
            } ])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'idioma']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'idioma'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'idioma'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Idioma::class,
                'query' => function (QueryBuilder $builder) use ($usuario) {
                    $builder
                        ->select('i')
                        ->from(Idioma::class, 'i')
                        ->join( 'i.hv', 'hv', 'WITH', 'hv.usuario = :usuario')
                        ->setParameter('usuario', $usuario);
                },
            ]);
    }
}