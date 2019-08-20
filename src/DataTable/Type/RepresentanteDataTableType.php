<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ButtonColumn\ButtonAttrRoute;
use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeModal;
use App\DataTable\Column\ButtonColumn\ButtonTypeModalBorrar;
use App\DataTable\Column\ButtonColumn\ButtonTypeRoute;
use App\DataTable\Column\ButtonColumn\DatatablePropertyAccessor;
use App\Entity\Representante;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class RepresentanteDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $convenio = $options['convenio'];
        $dataTable
            ->add('nombre', TextColumn::class, ['label' => 'Nombre', 'field' => 'u.primerNombre',
                'data' => function(Representante $representante) {
                    return $representante->getUsuario()->getNombreCompleto(true);
                }])
            ->add('correo', TextColumn::class, ['label' => 'Correo', 'field' => 'r.email', 'orderable' => false])
            ->add('rol', TextColumn::class, ['label' => 'Rol', 'field' => 'r.type', 'orderable' => false, 'render' => function($value){
                return "<span class='badge'>$value</span>";
            }])
            ->add('encargado', MapColumn::class, [ 'label' => 'Encargado', 'raw' => true, 'orderable' => false,
                'map' => [
                    true => '<i class="fas fa-check"></i>',
                    false => ''
                ]
            ])
            ->add('bcc', MapColumn::class, [ 'label' => 'Bcc', 'raw' => true, 'orderable' => false,
                'map' => [
                    true => '<i class="fas fa-check"></i>',
                    false => ''
                ]
            ])
            ->add('actions', ButtonColumn::class, [
                'label' => '',
                'field' => 'c.codigo',
                'orderable' => false,
                'buttons' => [
                    new ButtonTypeRoute('admin_convenio_representante_edit', [
                        'codigo' => new DatatablePropertyAccessor('convenio.codigo'),
                        'rid' => new DatatablePropertyAccessor('id')
                    ], 'fas fa-pencil-alt'),
                    new ButtonTypeModalBorrar('admin_convenio_representante_edit', [
                        'codigo' => new DatatablePropertyAccessor('convenio.codigo'),
                        'rid' => new DatatablePropertyAccessor('id')
                    ])
                ]
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Representante::class,
                'query' => function (QueryBuilder $builder) use ($convenio) {
                    $builder
                        ->select('r')
                        ->from(Representante::class, 'r')
                        ->join('r.convenio', 'c')
                        ->join('r.usuario', 'u')
                        ->where('c = :convenio')
                        ->setParameter('convenio', $convenio);
                }
            ])
        ;
    }
}