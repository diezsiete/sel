<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeRoute;
use App\DataTable\Column\ButtonColumn\DatatablePropertyAccessor;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORM\SearchCriteriaProvider;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;
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
        if($this->security->isGranted(['ROLE_ALLOWED_TO_SWITCH'], $this->security->getUser())) {
            $dataTable->add('actions', ButtonColumn::class, [
                'label' => '',
                'field' => 'u.identificacion',
                'buttons' => [
                    new ButtonTypeRoute('app_comprobantes', ['_switch_user'], 'fas fa-user-cog'),
                    new ButtonTypeRoute('admin_usuarios_editar',
                        ['id' => new DatatablePropertyAccessor('id')], 'fas fa-pencil-alt')
                ]
            ]);
        }
    }
}