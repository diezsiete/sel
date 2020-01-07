<?php

namespace App\DataTable\Type;

use App\DataTable\Adapter\GroupByORMAdapter;
use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Main\Usuario;
use App\Entity\Vacante\Vacante;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class VacanteDataTableType implements DataTableTypeInterface
{

    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var Security
     */
    private $security;

    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function configure(DataTable $dataTable, array $options)
    {
        /** @var Usuario $usuario */
        $usuario = $options['usuario'] || $this->security->getUser();


        $dataTable
            ->add('titulo', TextColumn::class, ['label' => 'Título', 'propertyPath' => '[0].titulo'])
            ->add('createdAt', DateTimeColumn::class, ['label' => 'Publicación', 'format' => 'Y-m-d',
                'propertyPath' => '[0].createdAt']);

        if($this->security->isGranted('ROLE_ADMIN_VACANTE', $usuario)) {
            $dataTable
                ->add('usuario', TextColumn::class, ['label' => 'Usuario',
                    'propertyPath' => '[0].usuario.primerNombre',
                    'render' => function ($val, $data) {
                        return $data[0]->getUsuario()->getNombrePrimeros();
                    }
                ]);
        }
        $dataTable
            ->add('id', ActionsColumn::class, [
                'label' => 'Acciones',
                'className' => 'actions',
                'orderable' => false,
                'propertyPath' => '[0].id',
                'actions' => [
                    [
                        'route' => ['admin_vacante_editar', ['vacante' => '[0].id']],
                        'icon' => 'fas fa-pencil-alt',
                        'tooltip' => 'Editar'
                    ],
                    [
                        'modal' => '#modalBasic',
                        'confirm' => ['admin_vacante_borrar', ['vacante' => '[0].id']],
                        'icon' => 'far fa-trash-alt',
                        'tooltip' => 'Borrar'
                    ],
                    [
                        'route' => ['admin_vacante_vid_aspirantes', ['vacante' => '[0].id']],
                        'text' => '[aspirantes]',
                        'tooltip' => 'Aspirantes'
                    ]
                ]
            ])
            ->createAdapter(GroupByORMAdapter::class, [
                'entity' => Vacante::class,
                'query' => function (QueryBuilder $builder) use ($usuario) {
                    $builder
                        ->select('v')
                        ->addSelect('COUNT(hvs) AS aspirantes')
                        ->from(Vacante::class, 'v')
                        ->leftJoin('v.hvs', 'hvs')
                        ->groupBy('v.id');
                    if(!$this->security->isGranted(['ROLE_VACANTES_ADMIN'])) {
                        $builder->addSelect('usuario')
                            ->join('v.usuario', 'usuario')
                            ->where('usuario = :usuario')
                            ->setParameter('usuario', $usuario);
                    }
                }
            ]);
    }
}