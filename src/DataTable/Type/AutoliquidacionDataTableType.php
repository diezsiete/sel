<?php


namespace App\DataTable\Type;


use App\DataTable\Column\ButtonColumn\ButtonColumn;
use App\DataTable\Column\ButtonColumn\ButtonTypeRoute;
use App\DataTable\Column\ButtonColumn\DatatablePropertyAccessor;
use App\Entity\Autoliquidacion\Autoliquidacion;
use App\Entity\Usuario;
use App\Repository\Autoliquidacion\AutoliquidacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORM\SearchCriteriaProvider;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class AutoliquidacionDataTableType implements DataTableTypeInterface
{


    /**
     * @var AutoliquidacionRepository
     */
    private $autoliquidacionRepository;
    /**
     * @var Security
     */
    private $security;

    public function __construct(AutoliquidacionRepository $autoliquidacionRepository, Security $security)
    {
        $this->autoliquidacionRepository = $autoliquidacionRepository;
        $this->security = $security;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('id', TextColumn::class, ['label' => 'id'])
            ->add('periodo', DateTimeColumn::class,
                ['label' => 'Nombre', 'format' => 'Y-m'])
            ->add('codigo', TextColumn::class,
                ['label' => 'Convenio codigo', 'field' => 'convenio.codigo'])
            ->add('nombre', TextColumn::class,[
                'label' => 'Convenio nombre', 'field' => 'convenio.nombre'
            ])
            ->add('porcentajeEjecucion', NumberColumn::class, ['label' => '%'])
            ->addOrderBy('id', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Autoliquidacion::class,
            ])
        ;
//        if($this->security->isGranted(['ROLE_ALLOWED_TO_SWITCH'], $this->security->getUser())) {
//
//        }

//        $dataTable->add('actions', ButtonColumn::class, [
//            'label' => '',
//            'field' => 'id',
//            'buttons' => [
//                new ButtonTypeRoute('app_comprobantes', ['_switch_user'], 'fas fa-user-cog'),
//                new ButtonTypeRoute('admin_usuarios_editar',
//                    ['id' => new DatatablePropertyAccessor('id')], 'fas fa-pencil-alt')
//            ]
//        ]);
    }
}