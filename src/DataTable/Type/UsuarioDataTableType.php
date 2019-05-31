<?php


namespace App\DataTable\Type;


use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORM\SearchCriteriaProvider;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;

class UsuarioDataTableType implements DataTableTypeInterface
{

    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
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
                'query' => function (QueryBuilder $builder, DataTableState $state) {
                    $builder
                        ->select('u')
                        ->from(Usuario::class, 'u');
                },
                'criteria' => [
                    function(QueryBuilder $builder, DataTableState $state) {
                        if($state->getGlobalSearch()) {
                            $this->usuarioRepository->userSearch($builder, $state->getGlobalSearch());
                        }
                    },
                ]
            ])
        ;
    }
}