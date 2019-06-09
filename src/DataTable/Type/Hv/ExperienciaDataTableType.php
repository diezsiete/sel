<?php


namespace App\DataTable\Type\Hv;


use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Estudio;
use App\Entity\Experiencia;
use Doctrine\ORM\QueryBuilder;
use Faker\Provider\Text;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ExperienciaDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $usuario = $options['usuario'];

        $dataTable
            ->add('empresa', TextColumn::class, ['label' => 'Nombre de la empresa'])
            ->add('cargo', TextColumn::class, ['label' => 'Cargo'])
            ->add('experiencia', TextColumn::class, ['label' => 'Area de experiencia', 'field' => 'area.nombre'])
            ->add('duracion', TextColumn::class, ['label' => 'Duración'])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'experiencia']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'experiencia'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'experiencia'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Experiencia::class,
                'query' => function (QueryBuilder $builder) use ($usuario) {
                    $builder
                        ->select('e')
                        ->from(Experiencia::class, 'e')
                        ->join('e.area', 'area')
                        ->join( 'e.hv', 'hv', 'WITH', 'hv.usuario = :usuario')
                        ->orderBy('e.id', 'DESC')
                        ->setParameter('usuario', $usuario);
                },
            ]);
    }
}