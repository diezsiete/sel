<?php

namespace App\DataTable\Type;

use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Vacante;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class VacanteDataTableType implements DataTableTypeInterface
{

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('titulo', TextColumn::class, ['label' => 'Título'])
            ->add('nombreCompleto', TextColumn::class, ['label' => 'Usuario'])
            ->add('createdAt', DateTimeColumn::class, ['label' => 'Publicación', 'format' => 'Y-m-d'])
            ->setTransformer(function ($row, Vacante $vacante) {
                $row['nombreCompleto'] = $vacante->getUsuario()->getNombreCompleto(true, true);
                return $row;
            })
            ->add('id', ActionsColumn::class, [
                'label' => 'Acciones',
                'className' => 'actions',
                'orderable' => false,
                'actions' => [
                    [
                        'route' => ['admin_vacante_editar', ['vacante' => 'id']],
                        'icon' => 'fas fa-pencil-alt',
                        'tooltip' => 'Editar'
                    ],
                    [
                        'modal' => '#modalBasic',
                        'confirm' => ['admin_vacante_borrar', ['vacante' => 'id']],
                        'icon' => 'far fa-trash-alt',
                        'tooltip' => 'Borrar'
                    ]
                ]
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Vacante::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('v')
                        ->from(Vacante::class, 'v');
                }
            ]);
    }
}