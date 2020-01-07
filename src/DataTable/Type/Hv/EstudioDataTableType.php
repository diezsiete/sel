<?php


namespace App\DataTable\Type\Hv;


use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\Entity\Hv\Estudio;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class EstudioDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $hv = $options['hv'];

        $dataTable
            ->add('codigo', TextColumn::class, ['label' => 'Codigo', 'field' => 'codigo.nombre'])
            ->add('nombre', TextColumn::class, ['label' => 'Título'])
            ->add('institucion', TextColumn::class, ['label' => 'Institución', 'field' => 'instituto.nombre'])
            ->add('fin', DateTimeColumn::class, ['label' => 'Fecha finalizacion', 'format' => 'Y-m-d'])
            ->add('id', ButtonColumn::class, ['label' => '', 'buttons' => [
                (new ButtonTypeModal('#modalForm', 'fas fa-pencil-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-get-url' => new ButtonAttrRoute('hv_entity_get', ['id', 'entity' => 'estudio']),
                    'data-update-url' => new ButtonAttrRoute('hv_entity_update', ['id', 'entity' => 'estudio'])
                ]),
                (new ButtonTypeModal('#modalBasic', 'far fa-trash-alt'))->setAttr([
                    'class' => 'modal-with-form',
                    'data-delete-url' => new ButtonAttrRoute('hv_entity_delete', ['id', 'entity' => 'estudio'])
                ])
            ]])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Estudio::class,
                'query' => function (QueryBuilder $builder) use ($hv) {
                    
                    $builder
                        ->select('e')
                        ->from(Estudio::class, 'e')
                        ->join('e.codigo', 'codigo')
                        ->join('e.instituto', 'instituto')
                        ->where('e.hv = :hv')
                        ->orderBy('e.id', 'DESC')
                        ->setParameter('hv', $hv);
                },
            ]);
    }
}