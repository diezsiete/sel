<?php


namespace App\DataTable\Type\Scraper;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use App\Entity\Scraper\Solicitud;
use App\Repository\Scraper\SolicitudRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class SolicitudHvDataTableType implements DataTableTypeInterface
{

    /**
     * @var SolicitudRepository
     */
    private $solicitudRepository;

    public function __construct(SolicitudRepository $solicitudRepository)
    {
        $this->solicitudRepository = $solicitudRepository;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'id', 'field' => 'u.identificacion'])
            ->add('nombres', TextColumn::class, ['label' => 'Nombres', 'field' => 'u.nombrePrimeros'])
            ->add('estado', MapColumn::class, [
                'label' => 'Estado',
                'field' => 's.estado',
                'map' => $this->solicitudRepository->getEstadoArray()
            ])
            ->add('createdAt', DateTimeColumn::class, ['label' => 'Created at', 'format' => 'Y-m-d H:i:s'])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 's.estado',
                'orderable' => false,
                'actions' => [
                    [
                        'modal' => '#modalTest',
                        'icon' => 'far fa-envelope',
                        'tooltip' => 'Message',
                        'data-id' => '.id',
                        'data-estado' => '.estado'
                    ],
                    [
                        'tooltip' => 'Cargar a novasoft',
                        'data-id' => '.id',
                        'class' => function($value) {
                            $class = 'btn btn-outline-primary';
                            switch ($value) {
                                case SolicitudRepository::EJECUTADO_EXITO:
                                    $class = 'btn btn-outline-success';
                                    break;
                                case SolicitudRepository::EJECUTADO_ERROR:
                                    $class = 'btn btn-outline-danger';
                                    break;
                                case SolicitudRepository::EJECUTANDO:
                                case SolicitudRepository::ESPERANDO_EN_COLA:
                                    $class .= ' spin';
                                    break;
                            }
                            return 'scraper ' . $class;
                        },
                        'icon' => function($value) {
                            $icon = 'fas fa-upload';
                            if($value === SolicitudRepository::EJECUTANDO || $value === SolicitudRepository::ESPERANDO_EN_COLA) {
                                $icon = 'fas fa-spinner fa-spin';
                            }
                            return $icon;
                        }
                    ],
                ]
            ])
            ->addOrderBy('createdAt', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Solicitud::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('s, hv, u')
                        ->from(Solicitud::class, 's')
                        ->join('s.hv', 'hv')
                        ->join('hv.usuario', 'u');
                },
            ])
        ;
    }
}