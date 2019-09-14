<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Security\Core\Security;

class UsuarioDataTableType implements DataTableTypeInterface
{

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(UsuarioRepository $usuarioRepository, Security $security)
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->security = $security;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'Identificación'])
            ->add('nombreCompleto', TextColumn::class, ['label' => 'Nombre'])
            ->addOrderBy('identificacion', DataTable::SORT_ASCENDING)
            ->setTransformer(function ($row, Usuario $usuario) {
                $row['nombreCompleto'] = $usuario->getNombreCompleto(false, true);
                return $row;
            })
            ->createAdapter(ORMAdapter::class, [
                'entity' => Usuario::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('u')
                        ->from(Usuario::class, 'u');
                },
                'criteria' => [
                    function(QueryBuilder $builder, DataTableState $state) {
                        if($state->getGlobalSearch()) {
                            $builder->andWhere(
                                $this->usuarioRepository->userSearchExpression($builder, $state->getGlobalSearch())
                            );
                        }
                    },
                ]
            ])
        ;
        if($this->security->isGranted(['ROLE_ADMIN_USUARIOS'], $this->security->getUser())) {
            $actions = [[
                'route' => ['admin_usuarios_editar', ['id' => 'id']],
                'icon' => 'fas fa-pencil-alt',
                'tooltip' => 'Editar'
            ]];
            if($this->security->isGranted(['ROLE_ALLOWED_TO_SWITCH'], $this->security->getUser())) {
                $actions[] = [
                    'route' => ['app_comprobantes', ['_switch_user']],
                    'icon' => 'fas fa-user-cog',
                    'tooltip' => 'Impersonar'
                ];
            }
            $dataTable->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'u.identificacion',
                'orderable' => false,
                'actions' => $actions
            ]);
        }
    }
}